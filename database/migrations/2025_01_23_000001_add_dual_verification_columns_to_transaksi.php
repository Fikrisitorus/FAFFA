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
            $table->tinyInteger('verified_by_nasabah')->default(0)->after('verified_by'); // 0 = pending, 1 = verified
            $table->tinyInteger('verified_by_admin')->default(0)->after('verified_by_nasabah'); // 0 = pending, 1 = verified
            $table->timestamp('verified_at_nasabah')->nullable()->after('verified_by_admin');
            $table->timestamp('verified_at_admin')->nullable()->after('verified_at_nasabah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['verified_by_nasabah', 'verified_by_admin', 'verified_at_nasabah', 'verified_at_admin']);
        });
    }
};
