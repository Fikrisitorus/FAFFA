<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added missing import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if jadwal_penjemputan column already exists
        if (!Schema::hasColumn('penjemputan', 'jadwal_penjemputan')) {
            Schema::table('penjemputan', function (Blueprint $table) {
                // Add new datetime column as nullable first
                $table->datetime('jadwal_penjemputan')->nullable()->after('pengepul_id');
            });
        }

        // Use a safer approach with raw SQL to handle invalid dates
        DB::statement("SET sql_mode = ''");
        
        // Clean up invalid data
        DB::statement('UPDATE penjemputan SET tanggal_penjemputan = CURDATE() WHERE tanggal_penjemputan = "0000-00-00" OR tanggal_penjemputan IS NULL');
        DB::statement('UPDATE penjemputan SET waktu_penjemputan = NOW() WHERE waktu_penjemputan = "0000-00-00 00:00:00" OR waktu_penjemputan IS NULL');
        
        // Now migrate the data safely
        DB::statement('UPDATE penjemputan SET jadwal_penjemputan = CONCAT(tanggal_penjemputan, " ", TIME(waktu_penjemputan))');
        
        // Restore sql_mode
        DB::statement("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");
        
        // Make the column not nullable after data migration
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->datetime('jadwal_penjemputan')->nullable(false)->change();
        });

        // Drop old columns
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_penjemputan', 'waktu_penjemputan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            // Add back old columns
            $table->date('tanggal_penjemputan')->after('pengepul_id');
            $table->datetime('waktu_penjemputan')->after('tanggal_penjemputan');
        });

        // Migrate data back
        DB::statement('UPDATE penjemputan SET tanggal_penjemputan = DATE(jadwal_penjemputan), waktu_penjemputan = jadwal_penjemputan');

        // Drop new column
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropColumn('jadwal_penjemputan');
        });
    }
};
