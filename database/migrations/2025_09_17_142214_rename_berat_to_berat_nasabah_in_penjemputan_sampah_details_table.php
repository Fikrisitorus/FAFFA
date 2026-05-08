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
        Schema::table('penjemputan_sampah_details', function (Blueprint $table) {
            // Rename kolom 'berat' menjadi 'berat_nasabah' jika belum ada
            if (Schema::hasColumn('penjemputan_sampah_details', 'berat') && !Schema::hasColumn('penjemputan_sampah_details', 'berat_nasabah')) {
                $table->renameColumn('berat', 'berat_nasabah');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan_sampah_details', function (Blueprint $table) {
            // Rename kembali kolom 'berat_nasabah' menjadi 'berat'
            if (Schema::hasColumn('penjemputan_sampah_details', 'berat_nasabah') && !Schema::hasColumn('penjemputan_sampah_details', 'berat')) {
                $table->renameColumn('berat_nasabah', 'berat');
            }
        });
    }
};
