<?php

namespace App\Filament\Pages;

use App\Models\Transaksi;
use App\Models\Penjemputan;
use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class UploadBuktiTransaksi extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-up';
    protected static string $view = 'filament.pages.upload-bukti-transaksi';
    protected static ?string $navigationLabel = 'Upload Bukti Transaksi';
    protected static ?string $title = 'Upload Bukti Transaksi';
    protected static ?string $slug = 'upload-bukti-transaksi';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('pengepul');
    }

    public $transaksiList = [];
    public $selectedPenjemputanId = null;
    public $activeTab = 'nasabah'; // 'nasabah' atau 'sistem'

    public function mount(): void
    {
        // Sync otomatis saat masuk ke halaman
        $this->syncPaymentStatus();
        $this->loadTransaksi();
    }
    
    /**
     * Sync payment status dari Midtrans (otomatis)
     */
    private function syncPaymentStatus(): void
    {
        try {
            // Jalankan sync di background tanpa blocking UI
            Artisan::call('payment:sync-status');
        } catch (\Exception $e) {
            // Log error tapi tidak tampilkan notifikasi agar tidak mengganggu user
            \Log::error('Auto sync payment status failed: ' . $e->getMessage());
        }
    }

    public function switchTab($tab): void
    {
        $this->activeTab = $tab;
        $this->loadTransaksi();
    }

    public function loadTransaksi(): void
    {
        $pengepulId = Auth::id();
        
        // Tentukan filter berdasarkan tab aktif
        if ($this->activeTab === 'nasabah') {
            // Transaksi ke Nasabah
            $transaksi = Transaksi::where('nasabah', true)
                ->where(function ($q) use ($pengepulId) {
                    $q->whereHas('penjemputan', function($qq) use ($pengepulId) {
                        $qq->where('pengepul_id', $pengepulId);
                    })->orWhere('pengepul_id', $pengepulId);
                })
                ->with(['penjemputan', 'jenisSampah', 'nasabah'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Transaksi Donasi ke Sistem
            $transaksi = Transaksi::where('sistem', true)
                ->where('nasabah', false)
                ->where(function ($q) use ($pengepulId) {
                    $q->whereHas('penjemputan', function($qq) use ($pengepulId) {
                        $qq->where('pengepul_id', $pengepulId);
                    })->orWhere('pengepul_id', $pengepulId);
                })
                ->with(['penjemputan', 'jenisSampah', 'nasabah'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Group by penjemputan_id
        $this->transaksiList = $transaksi->groupBy('penjemputan_id')->map(function ($items, $penjemputanId) {
            $first = $items->first();
            
            // Cek status: jika ada yang status = 99, maka ditolak
            $rejected = $items->contains(fn ($t) => (int)($t->status ?? 0) === 99);
            $alasanPenolakan = $rejected ? ($items->first(fn ($t) => (int)($t->status ?? 0) === 99)->alasan_penolakan ?? '') : '';
            
            // Tentukan verified berdasarkan tipe transaksi
            if ($this->activeTab === 'nasabah') {
                $verified = $items->every(fn ($t) => $t->verified_by_nasabah);
            } else {
                // Cek verified dari verified_by_admin (menggantikan status_verifikasi)
                $verified = $items->every(fn ($t) => (int)($t->verified_by_admin ?? 0) === 1);
            }
            
            // Tentukan kolom bukti berdasarkan tipe transaksi
            $buktiField = $this->activeTab === 'nasabah' ? 'gambar_bukti_nasabah' : 'gambar_bukti_sistem';
            
            return [
                'penjemputan_id' => $penjemputanId,
                'tanggal' => optional($first->created_at)->format('d/m/Y H:i'),
                'total_dana' => $items->sum('total_harga'),
                'verified' => $verified,
                'rejected' => $rejected,
                'alasan_penolakan' => $alasanPenolakan,
                'status' => $rejected ? 99 : ($verified ? 1 : 0),
                // Cek bukti dari kolom yang sesuai dengan tipe transaksi, fallback ke bukti_pembayaran
                'any_bukti' => $items->contains(fn ($t) => !empty($t->$buktiField) || !empty($t->bukti_pembayaran)),
                'bukti_pembayaran' => $first->$buktiField ?? $first->bukti_pembayaran, // ambil bukti dari kolom yang sesuai
                'items' => $items->values(),
            ];
        })->values();
    }


    protected function getHeaderActions(): array
    {
        // Tombol sync dihapus, sync otomatis saat mount
        return [];
    }


    public function uploadBuktiAction(): Action
    {
        return Action::make('uploadBukti')
            ->modalHeading('Upload Bukti Pembayaran')
            ->modalDescription(function () {
                if (!$this->selectedPenjemputanId) return '';
                
                $tipe = $this->activeTab === 'nasabah' ? 'Pembayaran ke Nasabah' : 'Donasi ke Sistem';
                
                return new \Illuminate\Support\HtmlString(
                    '<div class="space-y-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Upload bukti <strong>' . strtolower($tipe) . '</strong> untuk <strong>Penjemputan #' . $this->selectedPenjemputanId . '</strong>
                        </p>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Informasi</p>
                                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                        ✓ Bukti ini untuk semua transaksi dalam penjemputan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>'
                );
            })
            ->modalIcon('heroicon-o-cloud-arrow-up')
            ->modalIconColor('primary')
            ->form([
                FileUpload::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxSize(5120)
                    ->required()
                    ->helperText('Upload bukti pembayaran (Gambar atau PDF, maksimal 5MB)'),
                
                Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(3)
                    ->placeholder('Tambahkan catatan jika diperlukan...')
                    ->maxLength(500),
            ])
            ->modalSubmitActionLabel('Upload')
            ->modalCancelActionLabel('Batal')
            ->action(function (array $data) {
                if (!$this->selectedPenjemputanId) {
                    Notification::make()
                        ->title('Error')
                        ->body('Penjemputan tidak ditemukan!')
                        ->danger()
                        ->send();
                    return;
                }

                $pengepulId = Auth::id();

                // Tentukan filter berdasarkan tab aktif
                $query = Transaksi::where('penjemputan_id', $this->selectedPenjemputanId)
                    ->where(function ($q) use ($pengepulId) {
                        $q->whereHas('penjemputan', function($qq) use ($pengepulId) {
                            $qq->where('pengepul_id', $pengepulId);
                        })->orWhere('pengepul_id', $pengepulId);
                    });

                if ($this->activeTab === 'nasabah') {
                    $query->where('nasabah', true);
                } else {
                    $query->where('sistem', true)->where('nasabah', false);
                }

                $transaksiList = $query->get();

                if ($transaksiList->isEmpty()) {
                    Notification::make()
                        ->title('Error')
                        ->body('Transaksi tidak ditemukan!')
                        ->danger()
                        ->send();
                    return;
                }

                // Simpan file bukti
                $filePath = is_string($data['bukti_pembayaran']) 
                    ? $data['bukti_pembayaran'] 
                    : $data['bukti_pembayaran']->store('bukti-pembayaran', 'public');
                
                // Tentukan kolom bukti berdasarkan tipe transaksi
                $buktiField = $this->activeTab === 'nasabah' ? 'gambar_bukti_nasabah' : 'gambar_bukti_sistem';
                
                // Update semua transaksi dalam grup penjemputan ini
                foreach ($transaksiList as $transaksi) {
                    $updateData = [
                        'catatan' => $data['catatan'] ?? null,
                    ];
                    
                    // Simpan ke kolom yang sesuai dengan tipe transaksi
                    $updateData[$buktiField] = $filePath;
                    
                    // Juga simpan ke bukti_pembayaran untuk backward compatibility
                    $updateData['bukti_pembayaran'] = $filePath;

                    // Jika sebelumnya transaksi dalam status DITOLAK (99),
                    // reset kembali ke pending agar bisa diverifikasi ulang oleh nasabah/admin.
                    if ((int)($transaksi->status ?? 0) === 99) {
                        $updateData['status'] = 0; // 0 = pending / menunggu verifikasi
                        $updateData['alasan_penolakan'] = null;
                    }
                    
                    $transaksi->update($updateData);
                }

                Notification::make()
                    ->title('Berhasil')
                    ->body('Bukti pembayaran berhasil diupload untuk ' . $transaksiList->count() . ' transaksi!')
                    ->success()
                    ->send();

                $this->selectedPenjemputanId = null;
                $this->loadTransaksi();
            });
    }

    public function openUploadModal($penjemputanId)
    {
        $this->selectedPenjemputanId = $penjemputanId;
        $this->mountAction('uploadBukti');
    }
}
