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
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus kolom harga_per_kg (bisa dihitung dari relasi jenisSampah->harga)
            $table->dropColumn('harga_per_kg');
            
            // Hapus kolom status_verifikasi, verified_at, verified_by (sudah ada dual verification system)
            // Note: verified_by masih digunakan untuk relasi, jadi kita hapus foreign key dulu
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['status_verifikasi', 'verified_at', 'verified_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Restore harga_per_kg
            $table->decimal('harga_per_kg', 10, 2)->after('berat');
            
            // Restore status_verifikasi, verified_at, verified_by
            $table->tinyInteger('status_verifikasi')->default(0)->after('bukti_pembayaran');
            $table->timestamp('verified_at')->nullable()->after('status_verifikasi');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
