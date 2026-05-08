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
        Schema::create('penggunaan_danas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_penggunaan');
            $table->string('kategori');
            $table->text('deskripsi');
            $table->decimal('jumlah_pengeluaran', 15, 2);
            $table->string('bukti_pengeluaran')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_danas');
    }
};
