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
            $table->string('midtrans_order_id')->nullable()->after('weight_notes');
            $table->string('payment_status')->default('pending')->after('midtrans_order_id');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->timestamp('payment_time')->nullable()->after('payment_method');
            $table->decimal('total_amount', 10, 2)->nullable()->after('payment_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id', 'payment_status', 'payment_method', 'payment_time', 'total_amount'
            ]);
        });
    }
};