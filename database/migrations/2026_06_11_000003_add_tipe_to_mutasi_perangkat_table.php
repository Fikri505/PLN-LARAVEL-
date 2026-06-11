<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mutasi_perangkat', function (Blueprint $table) {
            if (!Schema::hasColumn('mutasi_perangkat', 'tipe')) {
                $table->enum('tipe', ['masuk', 'keluar'])->default('masuk')->after('stock_perangkat_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mutasi_perangkat', function (Blueprint $table) {
            if (Schema::hasColumn('mutasi_perangkat', 'tipe')) {
                $table->dropColumn('tipe');
            }
        });
    }
};