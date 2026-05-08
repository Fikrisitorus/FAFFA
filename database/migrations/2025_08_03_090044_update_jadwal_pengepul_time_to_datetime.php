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
        Schema::table('jadwal_pengepul', function (Blueprint $table) {
            // Ubah tipe data waktu_mulai dari time ke datetime
            $table->datetime('waktu_mulai')->change();
            
            // Ubah tipe data waktu_selesai dari time ke datetime
            $table->datetime('waktu_selesai')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengepul', function (Blueprint $table) {
            // Kembalikan tipe data waktu_mulai dari datetime ke time
            $table->time('waktu_mulai')->change();
            
            // Kembalikan tipe data waktu_selesai dari datetime ke time
            $table->time('waktu_selesai')->change();
        });
    }
};
