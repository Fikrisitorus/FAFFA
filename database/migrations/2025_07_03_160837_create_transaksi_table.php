<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
            $table->foreignId('pengepul_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penjemputan_id')->nullable()->constrained('penjemputan')->onDelete('set null');
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            $table->decimal('berat', 8, 2);
            $table->decimal('harga_per_kg', 10, 2);
            $table->decimal('total_harga', 15, 2);
            $table->datetime('tanggal_transaksi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};