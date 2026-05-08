<?php

namespace App\Observers;

use App\Models\Artikel;
use App\Helpers\CacheHelper;

class ArtikelObserver
{
    /**
     * Handle the Artikel "created" event.
     */
    public function created(Artikel $artikel): void
    {
        // Clear cache terkait artikel
        CacheHelper::clearArtikelRelatedCache();
    }

    /**
     * Handle the Artikel "updated" event.
     */
    public function updated(Artikel $artikel): void
    {
        // Clear cache setiap kali artikel diupdate
        CacheHelper::clearArtikelRelatedCache();
    }

    /**
     * Handle the Artikel "deleted" event.
     */
    public function deleted(Artikel $artikel): void
    {
        CacheHelper::clearArtikelRelatedCache();
    }
}


