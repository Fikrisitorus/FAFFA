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
            // Drop tanggal column
            $table->dropColumn('tanggal');
            
            // Add hari column
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'])->after('pengepul_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengepul', function (Blueprint $table) {
            // Drop hari column
            $table->dropColumn('hari');
            
            // Add back tanggal column
            $table->date('tanggal')->after('pengepul_id');
        });
    }
};
