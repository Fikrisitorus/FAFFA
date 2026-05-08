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
            $table->decimal('berat_nasabah', 8, 2)->default(0)->after('berat')->comment('Berat dari timbangan nasabah');
            $table->decimal('berat_verifikasi', 8, 2)->default(0)->after('berat_nasabah')->comment('Berat dari timbangan pengepul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan_sampah_details', function (Blueprint $table) {
            $table->dropColumn(['berat_nasabah', 'berat_verifikasi']);
        });
    }
};
