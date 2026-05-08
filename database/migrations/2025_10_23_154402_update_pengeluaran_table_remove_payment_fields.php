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
        Schema::table('pengeluaran', function (Blueprint $table) {
            // Hapus field yang tidak relevan
            $table->dropColumn(['metode_pembayaran', 'bukti_pembayaran']);
            
            // Tambah field yang relevan
            $table->text('catatan_pengeluaran')->nullable()->after('deskripsi');
            $table->string('penerima_pengeluaran')->nullable()->after('catatan_pengeluaran');
            $table->string('lokasi_pengeluaran')->nullable()->after('penerima_pengeluaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            // Kembalikan field yang dihapus
            $table->string('metode_pembayaran')->default('tunai');
            $table->string('bukti_pembayaran')->nullable();
            
            // Hapus field yang ditambahkan
            $table->dropColumn(['catatan_pengeluaran', 'penerima_pengeluaran', 'lokasi_pengeluaran']);
        });
    }
};