<?php

namespace App\Filament\Pages;

use App\Models\Transaksi;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class VerifikasiTransaksiSistem extends Page implements HasActions
{
    use InteractsWithActions;
    
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static string $view = 'filament.pages.verifikasi-transaksi-sistem';
    protected static ?string $navigationLabel = 'Verifikasi Donasi Sistem';
    protected static ?string $title = 'Verifikasi Donasi Sistem';
    protected static ?string $slug = 'verifikasi-transaksi-sistem';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('admin');
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
        $all = Transaksi::query()
            ->where('sistem', true)
            ->with(['penjemputan', 'jenisSampah'])
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get();

        $this->transaksiList = $all->groupBy('penjemputan_id')->map(function ($items) {
            $first = $items->first();
            $rejected = $items->contains(fn ($t) => (int)($t->status ?? 0) === 99);
            $verified = $items->every(fn ($t) => (int)($t->verified_by_admin ?? 0) === 1);

            return [
                'penjemputan_id' => $first->penjemputan_id,
                'tanggal' => optional($first->created_at)->format('d/m/Y H:i'),
                'created_at_timestamp' => optional($first->created_at)->timestamp ?? 0,
                'total_dana' => $items->sum('total_harga'),
                'verified_admin' => $verified,
                'rejected' => $rejected,
                'status' => $rejected ? 99 : ($verified ? 1 : 0),
                'alasan_penolakan' => $rejected ? ($items->first(fn ($t) => (int)($t->status ?? 0) === 99)->alasan_penolakan ?? '') : null,
                'any_bukti' => $items->contains(fn ($t) => !empty($t->gambar_bukti_sistem) || !empty($t->bukti_pembayaran)),
                'bukti_pembayaran' => $first->gambar_bukti_sistem ?? $first->bukti_pembayaran,
                'items' => $items->values(),
                'sort_order' => $rejected ? 2 : ($verified ? 3 : 1),
            ];
        })->values()
        ->sortBy([
            ['sort_order', 'asc'],
            function ($item) {
                return $item['sort_order'] == 1 ? -$item['created_at_timestamp'] : $item['created_at_timestamp'];
            },
        ])->values();
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
        $items = Transaksi::where('sistem', true)
            ->where('penjemputan_id', $this->selectedPenjemputanId)
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

        $detailHtml .= '<div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">';
        $detailHtml .= '<span class="font-semibold text-gray-900 dark:text-white">Total Dana Donasi:</span>';
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
                    Sudah Diterima Sistem
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
            ->modalHeading('Verifikasi Donasi Sistem')
            ->modalDescription(function () use ($detailHtml) {
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
                            Sudah Diterima Sistem
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
                            Detail donasi dari <strong>Penjemputan #' . $this->selectedPenjemputanId . '</strong>:
                        </p>
                        ' . $detailHtml . '
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Perhatian!</p>
                                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-400">
                                        ✓ Pastikan file bukti sudah divalidasi sebelum menyetujui donasi.
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
        $this->unmountAction();
    }

    public function verifyGroupByPenjemputan($penjemputanId): void
    {
        $items = Transaksi::where('sistem', true)
            ->where('penjemputan_id', $penjemputanId)
            ->get();

        foreach ($items as $t) {
            if ((int)($t->verified_by_admin ?? 0) === 0) {
                $t->verifyByAdmin(Auth::id());
            }
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Semua donasi pada penjemputan #' . $penjemputanId . ' telah diverifikasi.')
            ->success()
            ->send();

        $this->loadTransaksi();
    }

    public function rejectGroupByPenjemputan($penjemputanId, $alasanPenolakan): void
    {
        $items = Transaksi::where('sistem', true)
            ->where('penjemputan_id', $penjemputanId)
            ->get();

        foreach ($items as $t) {
            if ((int)($t->status ?? 0) !== 99 && (int)($t->verified_by_admin ?? 0) === 0) {
                $t->reject(Auth::id(), $alasanPenolakan);
            }
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Donasi pada penjemputan #' . $penjemputanId . ' telah ditolak!')
            ->success()
            ->send();

        $this->loadTransaksi();
    }
}
