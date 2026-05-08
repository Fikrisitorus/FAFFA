<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->foreignId('kelompok_id')->nullable()->after('nasabah_id')->constrained('kelompok')->onDelete('set null');
            $table->boolean('is_request_khusus')->default(false)->after('is_sorted');
        });
    }

    public function down(): void
    {
        Schema::table('penjemputan', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn(['kelompok_id', 'is_request_khusus']);
        });
    }
};