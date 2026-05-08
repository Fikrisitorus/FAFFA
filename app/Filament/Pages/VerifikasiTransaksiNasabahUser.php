<?php

namespace App\Filament\Pages;

use App\Models\Transaksi;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class VerifikasiTransaksiNasabahUser extends Page implements HasActions
{
    use InteractsWithActions;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static string $view = 'filament.pages.verifikasi-transaksi-nasabah-user';
    protected static ?string $navigationLabel = 'Verifikasi Transaksi Saya';
    protected static ?string $title = 'Verifikasi Transaksi Saya';
    protected static ?string $slug = 'verifikasi-transaksi-nasabah-user';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        // Izinkan untuk role kelompok_nasabah dan nasabah (user yang punya relasi nasabah)
        return $user && ($user->hasRole('kelompok_nasabah') || $user->hasRole('nasabah') || $user->nasabah);
    }

    public $transaksiList = [];
    public $selectedPenjemputanId = null;
    public $alasanPenolakan = '';
    public $showRejectForm = false;

    public function mount(): void
    {
        $this->loadTransaksi();
    }

    public function loadTransaksi(): void
    {
        $user = Auth::user();
        $kelompok = $user->kelompok;
        $nasabah = $user->nasabah;

        if (!$kelompok && !$nasabah) {
            $this->transaksiList = collect();
            return;
        }

        // Ambil transaksi nasabah (semua: pending + verified), lalu group per penjemputan
        $query = Transaksi::query()
            ->where('nasabah', true)
            ->with(['penjemputan', 'jenisSampah'])
            ->orderBy('created_at', 'desc');

        if ($kelompok) {
            $query->whereHas('penjemputan', function ($q) use ($kelompok) {
                $q->where('kelompok_id', $kelompok->id);
            });
        } else {
            $query->where('nasabah_id', $nasabah->id ?? 0);
        }

        $all = $query->limit(200)->get();

        $this->transaksiList = $all->groupBy('penjemputan_id')->map(function ($items) {
            $first = $items->first();
            // Cek status: jika ada yang status = 99, maka ditolak
            $rejected = $items->contains(fn ($t) => (int)($t->status ?? 0) === 99);
            // Cek verified dari verified_by_nasabah (integer: 0 = pending, 1 = verified)
            $verified = $items->every(fn ($t) => (int)($t->verified_by_nasabah ?? 0) === 1);
            
            return [
                'penjemputan_id' => $first->penjemputan_id,
                'tanggal' => optional($first->created_at)->format('d/m/Y H:i'),
                'created_at_timestamp' => optional($first->created_at)->timestamp ?? 0,
                'total_dana' => $items->sum('total_harga'),
                'verified_nasabah' => $verified,
                'rejected' => $rejected,
                'status' => $rejected ? 99 : ($verified ? 1 : 0),
                // Cek bukti dari gambar_bukti_nasabah, fallback ke bukti_pembayaran
                'any_bukti' => $items->contains(fn ($t) => !empty($t->gambar_bukti_nasabah) || !empty($t->bukti_pembayaran)),
                'bukti_pembayaran' => $first->gambar_bukti_nasabah ?? $first->bukti_pembayaran,
                'items' => $items->values(),
                // Untuk sorting: 1 = pending (atas), 2 = rejected, 3 = verified (bawah)
                'sort_order' => $rejected ? 2 : ($verified ? 3 : 1),
            ];
        })->values()
        ->sortBy([
            // Urutkan berdasarkan sort_order dulu (1 = pending, 2 = rejected, 3 = verified)
            ['sort_order', 'asc'],
            // Lalu urutkan berdasarkan timestamp
            // Untuk pending (sort_order = 1): terbaru dulu (descending)
            // Untuk rejected/verified (sort_order = 2/3): terlama dulu (ascending)
            function ($item) {
                return $item['sort_order'] == 1 ? -$item['created_at_timestamp'] : $item['created_at_timestamp'];
            }
        ])
        ->values();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->loadTransaksi();
                    Notification::make()
                        ->title('Berhasil')
                        ->body('Daftar transaksi diperbarui.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function verifyPenjemputanAction(): Action
    {
        // Ambil detail transaksi untuk ditampilkan di modal
        $user = Auth::user();
        $kelompok = $user->kelompok;
        $nasabah = $user->nasabah;
        
        // Ambil data penjemputan untuk cek donasi
        $penjemputan = \App\Models\Penjemputan::find($this->selectedPenjemputanId);
        $donationAmount = $penjemputan ? ($penjemputan->donation_amount ?? 0) : 0;
        $nasabahAmount = $penjemputan ? ($penjemputan->nasabah_amount ?? 0) : 0;
        $totalHarga = $penjemputan ? ($penjemputan->total_amount ?? 0) : 0;
        
        $items = Transaksi::where('nasabah', true)
            ->where('penjemputan_id', $this->selectedPenjemputanId)
            ->when($kelompok, function ($q) use ($kelompok) {
                $q->whereHas('penjemputan', function ($qq) use ($kelompok) {
                    $qq->where('kelompok_id', $kelompok->id);
                });
            }, function ($q) use ($nasabah) {
                $q->where('nasabah_id', $nasabah->id ?? 0);
            })
            ->with(['jenisSampah'])
            ->get();
        
        $totalDana = $items->sum('total_harga');
        $detailItems = $items->map(function ($item) {
            return [
                'jenis' => $item->jenisSampah->nama ?? '-',
                'berat' => number_format($item->berat, 2),
                'harga_per_kg' => number_format($item->jenisSampah->harga ?? 0, 0, ',', '.'),
                'total' => number_format($item->total_harga, 0, ',', '.'),
            ];
        })->toArray();
        
        $detailHtml = '<div class="mt-3 space-y-2 max-h-40 overflow-y-auto">';
        foreach ($detailItems as $detail) {
            $detailHtml .= '<div class="flex justify-between text-sm py-1 border-b border-gray-200 dark:border-gray-700">';
            $detailHtml .= '<span class="text-gray-600 dark:text-gray-400">' . $detail['jenis'] . ' (' . $detail['berat'] . ' kg × Rp ' . $detail['harga_per_kg'] . ')</span>';
            $detailHtml .= '<span class="font-medium text-gray-900 dark:text-white">Rp ' . $detail['total'] . '</span>';
            $detailHtml .= '</div>';
        }
        $detailHtml .= '</div>';
        
        // Informasi donasi jika ada
        $donationInfoHtml = '';
        if ($donationAmount > 0) {
            $donationInfoHtml = '<div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 space-y-2">';
            $donationInfoHtml .= '<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">';
            $donationInfoHtml .= '<div class="flex items-start gap-2">';
            $donationInfoHtml .= '<svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">';
            $donationInfoHtml .= '<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>';
            $donationInfoHtml .= '</svg>';
            $donationInfoHtml .= '<div class="flex-1">';
            $donationInfoHtml .= '<p class="text-sm font-medium text-blue-800 dark:text-blue-300">💝 Informasi Donasi</p>';
            $donationInfoHtml .= '<p class="mt-1 text-sm text-blue-700 dark:text-blue-400">';
            $donationInfoHtml .= 'Sebagian dana dari penjemputan ini telah didonasikan sebesar <strong>Rp ' . number_format($donationAmount, 0, ',', '.') . '</strong>';
            $donationInfoHtml .= '</p>';
            $donationInfoHtml .= '</div>';
            $donationInfoHtml .= '</div>';
            $donationInfoHtml .= '</div>';
            $donationInfoHtml .= '</div>';
        }
        
        $detailHtml .= '<div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">';
        $detailHtml .= '<span class="font-semibold text-gray-900 dark:text-white">Total Dana yang Diterima:</span>';
        $detailHtml .= '<span class="font-bold text-lg text-green-600 dark:text-green-400">Rp ' . number_format($totalDana, 0, ',', '.') . '</span>';
        $detailHtml .= '</div>';
        
        $rejectFormHtml = '';
        if ($this->showRejectForm) {
            $rejectFormHtml = '<div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea 
                    wire:model="alasanPenolakan"
                    rows="4"
                    placeholder="Masukkan alasan penolakan transaksi..."
                    style="background-color: rgb(255, 255, 255) !important; opacity: 1 !important;"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                    required></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Alasan penolakan wajib diisi</p>
                <div class="mt-3 flex justify-end">
                    <button 
                        type="button"
                        wire:click="handleRejectAction"
                        style="background-color: rgb(220, 38, 38) !important; opacity: 1 !important;"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700">
                        Submit Penolakan
                    </button>
                </div>
            </div>';
        }
        
        $buttonsHtml = '<div class="mt-6 space-y-3">
            <div class="flex gap-3 justify-center">
                <button 
                    type="button"
                    wire:click="handleVerifyAction"
                    style="background-color: rgb(22, 163, 74) !important; opacity: 1 !important; border: none !important;"
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-600 dark:hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Sudah Terima Dana
                </button>
                <button 
                    type="button"
                    wire:click="toggleRejectForm"
                    style="background-color: rgb(255, 255, 255) !important; border: 2px solid rgb(252, 165, 165) !important; opacity: 1 !important;"
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-800 dark:text-red-400 dark:border-red-600 dark:hover:bg-red-900/20 dark:hover:border-red-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Tolak
                </button>
            </div>
            <div class="flex justify-center">
                <button 
                    type="button"
                    wire:click="closeModal"
                    style="background-color: rgb(255, 255, 255) !important; border: 1px solid rgb(209, 213, 219) !important; opacity: 1 !important;"
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-500">
                    Batal
                </button>
            </div>
            ' . $rejectFormHtml . '
        </div>';

        return Action::make('verifyPenjemputan')
            ->modalHeading('Verifikasi Transaksi')
            ->modalDescription(function () use ($detailHtml, $donationInfoHtml) {
                // Rebuild buttons HTML setiap kali modal description di-render
                $rejectFormHtml = '';
                if ($this->showRejectForm) {
                    $rejectFormHtml = '<div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            wire:model="alasanPenolakan"
                            rows="4"
                            placeholder="Masukkan alasan penolakan transaksi..."
                            style="background-color: rgb(255, 255, 255) !important; opacity: 1 !important;"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                            required></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Alasan penolakan wajib diisi</p>
                        <div class="mt-3 flex justify-end">
                            <button 
                                type="button"
                                wire:click="handleRejectAction"
                                style="background-color: rgb(220, 38, 38) !important; opacity: 1 !important; border: none !important;"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700">
                                Submit Penolakan
                            </button>
                        </div>
                    </div>';
                }
                
                $buttonsHtml = '<div class="mt-6 space-y-3">
                    <div class="flex gap-3 justify-center">
                        <button 
                            type="button"
                            wire:click="handleVerifyAction"
                            style="background-color: rgb(22, 163, 74) !important; opacity: 1 !important; border: none !important;"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-600 dark:hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Sudah Terima Dana
                        </button>
                        <button 
                            type="button"
                            wire:click="toggleRejectForm"
                            style="background-color: rgb(255, 255, 255) !important; border: 2px solid rgb(252, 165, 165) !important; opacity: 1 !important;"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-800 dark:text-red-400 dark:border-red-600 dark:hover:bg-red-900/20 dark:hover:border-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak
                        </button>
                    </div>
                    <div class="flex justify-center">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            style="background-color: rgb(255, 255, 255) !important; border: 1px solid rgb(209, 213, 219) !important; opacity: 1 !important;"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-500">
                            Batal
                        </button>
                    </div>
                    ' . $rejectFormHtml . '
                </div>';
                
                return new \Illuminate\Support\HtmlString(
                    '<div class="space-y-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Detail transaksi dari <strong>Penjemputan #' . $this->selectedPenjemputanId . '</strong>:
                        </p>
                        ' . $detailHtml . '
                        ' . $donationInfoHtml . '
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Perhatian!</p>
                                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-400">
                                        ✓ Pastikan uang sudah diterima sesuai dengan jumlah yang ditampilkan
                                    </p>
                                </div>
                            </div>
                        </div>
                        ' . $buttonsHtml . '
                    </div>'
                );
            })
            ->modalIcon('heroicon-o-check-circle')
            ->modalIconColor('success')
            ->modalWidth('lg')
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }

    public function openVerifyModal($penjemputanId)
    {
        $this->selectedPenjemputanId = $penjemputanId;
        $this->showRejectForm = false;
        $this->alasanPenolakan = '';
        $this->mountAction('verifyPenjemputan');
    }
    
    public function updatedShowRejectForm()
    {
        // Re-render modal description saat showRejectForm berubah
        $this->dispatch('$refresh');
    }

    public function toggleRejectForm()
    {
        $this->showRejectForm = !$this->showRejectForm;
        if (!$this->showRejectForm) {
            $this->alasanPenolakan = '';
        }
    }

    public function handleVerifyAction()
    {
        $this->verifyGroupByPenjemputan($this->selectedPenjemputanId);
        $this->selectedPenjemputanId = null;
        $this->showRejectForm = false;
        $this->alasanPenolakan = '';
        $this->unmountAction();
    }

    public function handleRejectAction()
    {
        if (empty($this->alasanPenolakan)) {
            Notification::make()
                ->title('Error')
                ->body('Alasan penolakan wajib diisi!')
                ->danger()
                ->send();
            return;
        }
        $this->rejectGroupByPenjemputan($this->selectedPenjemputanId, $this->alasanPenolakan);
        $this->selectedPenjemputanId = null;
        $this->showRejectForm = false;
        $this->alasanPenolakan = '';
        $this->unmountAction();
    }

    public function closeModal()
    {
        $this->selectedPenjemputanId = null;
        $this->showRejectForm = false;
        $this->alasanPenolakan = '';
        // Tutup modal dengan unmount action
        $this->unmountAction();
    }

    public function verifyTransaksi($transaksiId): void
    {
        $user = Auth::user();
        $nasabah = $user->nasabah;
        
        if (!$nasabah) {
            Notification::make()
                ->title('Error')
                ->body('Anda bukan nasabah!')
                ->danger()
                ->send();
            return;
        }

        $transaksi = Transaksi::findOrFail($transaksiId);
        
        // Validasi bahwa ini transaksi nasabah yang login
        if ($transaksi->nasabah_id !== $nasabah->id) {
            Notification::make()
                ->title('Error')
                ->body('Anda tidak memiliki akses ke transaksi ini!')
                ->danger()
                ->send();
            return;
        }

        // Validasi bahwa ini transaksi nasabah
        if (!$transaksi->nasabah) {
            Notification::make()
                ->title('Error')
                ->body('Ini bukan transaksi nasabah!')
                ->danger()
                ->send();
            return;
        }

        // Verifikasi transaksi
        $transaksi->verifyByNasabah($nasabah->id);

        Notification::make()
            ->title('Berhasil')
            ->body('Transaksi berhasil diverifikasi! Uang sudah masuk ke saldo Anda.')
            ->success()
            ->send();
    }

    // Verifikasi seluruh transaksi dalam satu penjemputan (untuk user/kelompok)
    public function verifyGroupByPenjemputan($penjemputanId): void
    {
        $user = Auth::user();
        $kelompok = $user->kelompok;
        $nasabah = $user->nasabah;

        $items = Transaksi::where('nasabah', true)
            ->where('penjemputan_id', $penjemputanId)
            ->when($kelompok, function ($q) use ($kelompok) {
                $q->whereHas('penjemputan', function ($qq) use ($kelompok) {
                    $qq->where('kelompok_id', $kelompok->id);
                });
            }, function ($q) use ($nasabah) {
                $q->where('nasabah_id', $nasabah->id ?? 0);
            })
            ->get();

        foreach ($items as $t) {
            if ((int)($t->verified_by_nasabah ?? 0) === 0) {
                $t->verifyByNasabah($nasabah->id ?? 0);
            }
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Semua transaksi pada penjemputan #' . $penjemputanId . ' telah Anda verifikasi.')
            ->success()
            ->send();

        $this->loadTransaksi();
    }


    // Method untuk menolak seluruh transaksi dalam satu penjemputan (group)
    public function rejectGroupByPenjemputan($penjemputanId, $alasanPenolakan): void
    {
        $user = Auth::user();
        $kelompok = $user->kelompok;
        $nasabah = $user->nasabah;

        $items = Transaksi::where('nasabah', true)
            ->where('penjemputan_id', $penjemputanId)
            ->when($kelompok, function ($q) use ($kelompok) {
                $q->whereHas('penjemputan', function ($qq) use ($kelompok) {
                    $qq->where('kelompok_id', $kelompok->id);
                });
            }, function ($q) use ($nasabah) {
                $q->where('nasabah_id', $nasabah->id ?? 0);
            })
            ->get();

        foreach ($items as $t) {
            // Hanya tolak jika belum diverifikasi atau ditolak
            if ((int)($t->status ?? 0) !== 99 && (int)($t->verified_by_nasabah ?? 0) === 0) {
                $t->reject($nasabah->id ?? 0, $alasanPenolakan);
            }
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Transaksi pada penjemputan #' . $penjemputanId . ' telah ditolak!')
            ->success()
            ->send();

        $this->selectedPenjemputanId = null;
        $this->alasanPenolakan = '';
        $this->loadTransaksi();
    }
}
