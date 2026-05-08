<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjemputan_sampah_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjemputan_id')->constrained('penjemputan')->onDelete('cascade');
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            $table->decimal('berat', 8, 2)->default(0); // berat dalam kg
            $table->decimal('harga_per_kg', 10, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjemputan_sampah_details');
    }
};
