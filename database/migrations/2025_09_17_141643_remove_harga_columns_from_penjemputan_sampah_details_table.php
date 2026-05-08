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
            $table->dropColumn(['harga_per_kg', 'total_harga']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan_sampah_details', function (Blueprint $table) {
            $table->decimal('harga_per_kg', 10, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
        });
    }
};
