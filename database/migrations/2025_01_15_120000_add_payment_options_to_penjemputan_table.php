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
            $table->enum('payment_option', ['take_all', 'donate_all', 'donate_partial'])->default('take_all')->after('status');
            $table->decimal('donation_amount', 15, 2)->default(0)->after('payment_option');
            $table->decimal('nasabah_amount', 15, 2)->default(0)->after('donation_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropColumn(['payment_option', 'donation_amount', 'nasabah_amount']);
        });
    }
};
