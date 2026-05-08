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
            $table->dropColumn(['kapasitas_maksimal', 'kapasitas_terisi', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengepul', function (Blueprint $table) {
            $table->integer('kapasitas_maksimal')->default(10);
            $table->integer('kapasitas_terisi')->default(0);
            $table->enum('status', ['available', 'full', 'completed'])->default('available');
        });
    }
};
