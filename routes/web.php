<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route; 
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // Cache data transparansi donasi (5 menit)
    $totalDonasiMasuk = \Illuminate\Support\Facades\Cache::remember('homepage_total_donasi', 300, function () {
        return \App\Models\Transaksi::where('sistem', true)
            ->where('nasabah', false)
            ->whereHas('penjemputan', function($query) {
                $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
            })
            ->sum('total_harga');
    });
    
    $totalPengeluaran = \Illuminate\Support\Facades\Cache::remember('homepage_total_pengeluaran', 300, function () {
        return \App\Models\Pengeluaran::where('status', \App\Models\Pengeluaran::STATUS_APPROVED)
            ->sum('jumlah');
    });
    
    $saldoDonasi = $totalDonasiMasuk - $totalPengeluaran;
    
    // Cache riwayat pengeluaran (5 menit)
    $riwayatPengeluaran = \Illuminate\Support\Facades\Cache::remember('homepage_riwayat_pengeluaran', 300, function () {
        return \App\Models\Pengeluaran::where('status', \App\Models\Pengeluaran::STATUS_APPROVED)
            ->with('approvedBy')
            ->orderBy('tanggal_pengeluaran', 'desc')
            ->limit(10)
            ->get();
    });
    
    // Cache artikel terbaru (10 menit)
    $artikelTerbaru = \Illuminate\Support\Facades\Cache::remember('homepage_artikel_terbaru', 600, function () {
        return \App\Models\Artikel::published()
            ->with('author')
            ->latest('published_at')
            ->limit(3)
            ->get();
    });
    
    return view('welcome', [
        'totalDonasiMasuk' => $totalDonasiMasuk,
        'totalPengeluaran' => $totalPengeluaran,
        'saldoDonasi' => $saldoDonasi,
        'riwayatPengeluaran' => $riwayatPengeluaran,
        'artikelTerbaru' => $artikelTerbaru,
    ]);
});

// Halaman transparansi lengkap (donasi masuk & pengeluaran)
Route::get('/transparansi', function () {
    $totalDonasiMasuk = \App\Models\Transaksi::where('sistem', true)
        ->where('nasabah', false)
        ->whereHas('penjemputan', function($query) {
            $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
        })
        ->sum('total_harga');

    $totalPengeluaran = \App\Models\Pengeluaran::where('status', \App\Models\Pengeluaran::STATUS_APPROVED)
        ->sum('jumlah');

    $saldoDonasi = $totalDonasiMasuk - $totalPengeluaran;

    // Semua donasi sistem (masuk)
    $donasiMasuk = \App\Models\Transaksi::where('sistem', true)
        ->where('nasabah', false)
        ->whereHas('penjemputan', function($query) {
            $query->whereIn('payment_option', ['donate_all', 'donate_partial']);
        })
        ->with(['penjemputan', 'pengepul'])
        ->orderBy('tanggal_transaksi', 'desc')
        ->get();

    // Semua pengeluaran approved
    $semuaPengeluaran = \App\Models\Pengeluaran::where('status', \App\Models\Pengeluaran::STATUS_APPROVED)
        ->with('approvedBy')
        ->orderBy('tanggal_pengeluaran', 'desc')
        ->get();

    return view('pages.transparansi', [
        'totalDonasiMasuk' => $totalDonasiMasuk,
        'totalPengeluaran' => $totalPengeluaran,
        'saldoDonasi' => $saldoDonasi,
        'donasiMasuk' => $donasiMasuk,
        'semuaPengeluaran' => $semuaPengeluaran,
    ]);
})->name('transparansi.index');

// Public Artikel Routes (untuk guest/public)
Route::get('/artikel', [App\Http\Controllers\ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}', [App\Http\Controllers\ArtikelController::class, 'show'])->name('artikel.show');

// Public Pages
Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('pages.terms-of-service');
})->name('terms-of-service');

// Admin routes for payment status update (using Filament instead)
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/payment-status', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('admin.payment-status');
//     Route::post('/admin/update-payment/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('admin.update-payment');
// });

Route::get('/dashboard', function () {
    return redirect('/admin');
})->middleware(['auth', 'verified', 'redirect.role'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Route untuk membatalkan penjemputan (untuk nasabah/kelompok)
    Route::post('/admin/penjemputan/{id}/batal', [App\Http\Controllers\PenjemputanController::class, 'batalPenjemputan'])
        ->name('filament.admin.penjemputan.batal');
});

// Routes untuk penjemputan (FCFS system)
Route::middleware(['auth', 'role:pengepul'])->group(function () {
    Route::post('/admin/penjemputan/{id}/ambil-order', [App\Http\Controllers\PenjemputanController::class, 'ambilOrder'])
        ->name('filament.admin.penjemputan.ambil-order');
    Route::post('/admin/penjemputan/{id}/mulai', [App\Http\Controllers\PenjemputanController::class, 'mulaiPenjemputan'])
        ->name('filament.admin.penjemputan.mulai');
    Route::post('/admin/penjemputan/{id}/selesai', [App\Http\Controllers\PenjemputanController::class, 'selesaiPenjemputan'])
        ->name('filament.admin.penjemputan.selesai');
});

// Routes untuk PengepulController (verifikasi berat + pembayaran)
Route::middleware(['auth', 'role:pengepul'])->group(function () {
    Route::post('/pengepul/ambil-order/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'ambilOrder'])
        ->name('pengepul.ambil-order');
    Route::get('/pengepul/verifikasi-berat/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'verifikasiBerat'])
        ->name('pengepul.verifikasi-berat');
    Route::post('/pengepul/verifikasi-berat/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'prosesVerifikasiBerat'])
        ->name('pengepul.proses-verifikasi-berat');
    Route::get('/pengepul/pembayaran/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'pembayaran'])
        ->name('pengepul.pembayaran');
    Route::post('/pengepul/get-snap-token/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'getSnapToken'])
        ->name('pengepul.get-snap-token');
    Route::post('/pengepul/pembayaran/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'prosesPembayaran'])
        ->name('pengepul.proses-pembayaran');
    Route::get('/pengepul/payment-success/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'paymentSuccess'])
        ->name('pengepul.payment-success');
    Route::get('/pengepul/payment-pending/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'paymentPending'])
        ->name('pengepul.payment-pending');
    Route::get('/pengepul/payment-error/{penjemputan}', [App\Http\Controllers\PengepulController::class, 'paymentError'])
        ->name('pengepul.payment-error');
});

// Redirect dari area admin ke halaman pembayaran publik agar kompatibel dengan navigasi Filament
Route::middleware(['auth', 'role:pengepul'])->group(function () {
    Route::get('/admin/pembayaran/{penjemputan}', function (\App\Models\Penjemputan $penjemputan) {
        return redirect()->route('pengepul.pembayaran', $penjemputan->id);
    });
});

// API untuk pengecekan penjemputan baru
Route::middleware(['auth', 'role:pengepul'])->group(function () {
    Route::get('/admin/api/penjemputan/check-new', function () {
        $user = auth()->user();
        $lastCheck = session('last_penjemputan_check', now()->subMinutes(5));
        
        $newPenjemputan = \App\Models\Penjemputan::where('pengepul_id', $user->id)
            ->where('status', 'assigned')
            ->where('created_at', '>', $lastCheck)
            ->first();
            
        if ($newPenjemputan) {
            session(['last_penjemputan_check' => now()]);
            return response()->json([
                'hasNew' => true,
                'message' => "Penjemputan baru dari {$newPenjemputan->nasabah->nama}",
                'penjemputanId' => $newPenjemputan->id
            ]);
        }
        
        return response()->json(['hasNew' => false]);
    });
});

// Midtrans Webhook (no auth required)
Route::post('/payment/webhook', [App\Http\Controllers\PengepulController::class, 'handleWebhook'])
    ->name('payment.webhook');

// API untuk check payment status
Route::get('/api/check-pending-payments', [\App\Http\Controllers\Api\PaymentController::class, 'checkPendingPayments']);
Route::post('/api/trigger-sync', [\App\Http\Controllers\Api\PaymentController::class, 'triggerSync']);

// API untuk notifications
Route::middleware('auth')->group(function () {
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
    Route::get('/api/notifications/recent', [\App\Http\Controllers\Api\NotificationController::class, 'recent']);
    Route::get('/api/notifications/check-new', [\App\Http\Controllers\Api\NotificationController::class, 'checkNew']);
    Route::post('/api/notifications/{id}/mark-as-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    Route::post('/api/notifications/mark-all-as-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
});

// Debug endpoint: cek kesiapan pembayaran
Route::middleware(['auth', 'role:pengepul'])->group(function () {
	Route::get('/pengepul/pembayaran-debug/{penjemputan}', function (\App\Models\Penjemputan $penjemputan) {
		$userId = auth()->id();
		return response()->json([
			'penjemputan_id' => $penjemputan->id,
			'auth_id' => $userId,
			'pengepul_id' => $penjemputan->pengepul_id,
			'owner_ok' => $penjemputan->pengepul_id === $userId,
			'status' => $penjemputan->status,
			'status_ok' => in_array($penjemputan->status, ['weight_verified', 'on_progress']),
			'details_count' => $penjemputan->sampahDetails->count(),
			'verified_count' => $penjemputan->sampahDetails->where('berat_verifikasi', '>', 0)->count(),
		]);
	});
});

require __DIR__.'/auth.php';
