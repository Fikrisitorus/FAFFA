<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['plastik', 'kertas', 'logam', 'kaca', 'lainnya']);
            $table->text('deskripsi')->nullable();
            $table->enum('satuan', ['kg', 'gram', 'pcs', 'liter']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_sampah');
    }
};