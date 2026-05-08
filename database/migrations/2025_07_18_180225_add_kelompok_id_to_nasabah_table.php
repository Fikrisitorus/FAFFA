<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            if (!Schema::hasColumn('nasabah', 'kelompok_id')) {
                $table->foreignId('kelompok_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('kelompok')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            if (Schema::hasColumn('nasabah', 'kelompok_id')) {
                $table->dropForeign(['kelompok_id']);
                $table->dropColumn('kelompok_id');
            }
        });
    }
};
