<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * Clear homepage cache
     */
    public static function clearHomepageCache(): void
    {
        Cache::forget('homepage_total_donasi');
        Cache::forget('homepage_total_pengeluaran');
        Cache::forget('homepage_riwayat_pengeluaran');
        Cache::forget('homepage_artikel_terbaru');
    }

    /**
     * Clear artikel cache
     */
    public static function clearArtikelCache(): void
    {
        // Clear all artikel index pages (assuming max 100 pages)
        for ($i = 1; $i <= 100; $i++) {
            Cache::forget("artikel_index_page_{$i}");
        }
    }

    /**
     * Clear all cache related to transaksi
     */
    public static function clearTransaksiCache(): void
    {
        self::clearHomepageCache();
    }

    /**
     * Clear all cache related to pengeluaran
     */
    public static function clearPengeluaranCache(): void
    {
        self::clearHomepageCache();
    }

    /**
     * Clear all cache related to artikel
     */
    public static function clearArtikelRelatedCache(): void
    {
        self::clearHomepageCache();
        self::clearArtikelCache();
    }
}


