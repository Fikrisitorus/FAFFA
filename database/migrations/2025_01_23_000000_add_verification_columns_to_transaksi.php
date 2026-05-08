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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('total_harga');
            $table->tinyInteger('status_verifikasi')->default(0)->after('bukti_pembayaran'); // 0 = pending, 1 = verified
            $table->timestamp('verified_at')->nullable()->after('status_verifikasi');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['bukti_pembayaran', 'status_verifikasi', 'verified_at', 'verified_by']);
        });
    }
};
