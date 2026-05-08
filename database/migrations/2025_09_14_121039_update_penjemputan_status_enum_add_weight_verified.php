<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the status enum to include weight_verified
        DB::statement("ALTER TABLE penjemputan MODIFY COLUMN status ENUM('pending', 'assigned', 'on_progress', 'weight_verified', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE penjemputan MODIFY COLUMN status ENUM('pending', 'assigned', 'on_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};