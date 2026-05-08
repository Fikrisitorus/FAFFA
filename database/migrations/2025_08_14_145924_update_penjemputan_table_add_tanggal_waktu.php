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
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->date('tanggal_penjemputan')->after('kelompok_id')->nullable();
            $table->time('waktu_penjemputan')->after('tanggal_penjemputan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_penjemputan', 'waktu_penjemputan']);
        });
    }
};
