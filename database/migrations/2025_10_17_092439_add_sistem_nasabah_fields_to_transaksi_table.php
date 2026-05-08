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
            // Field untuk membedakan tujuan transaksi
            $table->boolean('sistem')->default(false)->after('catatan');
            $table->boolean('nasabah')->default(false)->after('sistem');
            
            // Field untuk bukti transaksi
            $table->string('gambar_bukti_nasabah')->nullable()->after('nasabah');
            $table->string('gambar_bukti_sistem')->nullable()->after('gambar_bukti_nasabah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'sistem',
                'nasabah', 
                'gambar_bukti_nasabah',
                'gambar_bukti_sistem'
            ]);
        });
    }
};
