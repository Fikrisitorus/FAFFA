<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjemputan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
            $table->foreignId('pengepul_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tanggal_penjemputan');
            $table->datetime('waktu_penjemputan');
            $table->text('alamat_penjemputan');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'assigned', 'on_progress', 'completed', 'cancelled'])->default('pending');
            $table->boolean('is_sorted')->default(false);
            $table->datetime('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjemputan');
    }
};