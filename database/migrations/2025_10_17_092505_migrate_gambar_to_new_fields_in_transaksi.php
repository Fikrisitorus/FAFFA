<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data gambar lama ke field baru
        // Asumsi: data lama adalah transaksi ke nasabah (karena belum ada sistem)
        \DB::table('transaksi')
            ->whereNotNull('gambar')
            ->update([
                'nasabah' => true,
                'gambar_bukti_nasabah' => \DB::raw('gambar')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: set nasabah = false dan clear gambar_bukti_nasabah
        \DB::table('transaksi')
            ->where('nasabah', true)
            ->whereNotNull('gambar_bukti_nasabah')
            ->update([
                'nasabah' => false,
                'gambar_bukti_nasabah' => null
            ]);
    }
};
