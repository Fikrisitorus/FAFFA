<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sedekah_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
            $table->foreignId('kelompok_id')->nullable()->constrained('kelompok')->onDelete('set null');
            $table->decimal('jumlah_sedekah', 15, 2);
            $table->decimal('persentase', 5, 2)->default(50); // default 50%
            $table->datetime('tanggal_sedekah');
            $table->tinyInteger('bulan_sedekah'); // 1-12
            $table->year('tahun_sedekah');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'used'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sedekah_sampah');
    }
};