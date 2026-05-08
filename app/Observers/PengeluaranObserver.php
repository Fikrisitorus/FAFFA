<?php

namespace App\Observers;

use App\Models\Pengeluaran;
use App\Helpers\CacheHelper;

class PengeluaranObserver
{
    /**
     * Handle the Pengeluaran "created" event.
     */
    public function created(Pengeluaran $pengeluaran): void
    {
        // Clear cache terkait pengeluaran
        CacheHelper::clearPengeluaranCache();
    }

    /**
     * Handle the Pengeluaran "updated" event.
     */
    public function updated(Pengeluaran $pengeluaran): void
    {
        // Clear cache jika status berubah (terutama jika di-approve)
        if ($pengeluaran->wasChanged('status')) {
            CacheHelper::clearPengeluaranCache();
        }
    }

    /**
     * Handle the Pengeluaran "deleted" event.
     */
    public function deleted(Pengeluaran $pengeluaran): void
    {
        CacheHelper::clearPengeluaranCache();
    }
}


