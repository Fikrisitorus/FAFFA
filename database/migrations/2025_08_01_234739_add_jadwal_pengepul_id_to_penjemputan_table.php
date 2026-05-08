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
            $table->foreignId('jadwal_pengepul_id')->nullable()->after('pengepul_id')->constrained('jadwal_pengepul')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropForeign(['jadwal_pengepul_id']);
            $table->dropColumn('jadwal_pengepul_id');
        });
    }
};
