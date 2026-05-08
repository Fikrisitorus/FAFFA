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
            $table->decimal('estimasi_berat', 8, 2)->nullable()->after('catatan');
            $table->decimal('berat_final', 8, 2)->nullable()->after('estimasi_berat');
            $table->enum('weight_status', ['estimated', 'verified', 'final'])->default('estimated')->after('berat_final');
            $table->boolean('self_weighted')->default(false)->after('weight_status');
            $table->decimal('berat_difference', 8, 2)->nullable()->after('self_weighted');
            $table->text('weight_notes')->nullable()->after('berat_difference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropColumn([
                'estimasi_berat',
                'berat_final',
                'weight_status',
                'self_weighted',
                'berat_difference',
                'weight_notes'
            ]);
        });
    }
};
