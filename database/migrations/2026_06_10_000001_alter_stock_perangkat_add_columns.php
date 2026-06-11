<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_perangkat', function (Blueprint $table) {
            if (Schema::hasColumn('stock_perangkat', 'supplai')) {
                $table->dropColumn('supplai');
            }

            if (!Schema::hasColumn('stock_perangkat', 'jumlah')) {
                $table->unsignedInteger('jumlah')->default(1)->after('type_barang');
            }

            if (!Schema::hasColumn('stock_perangkat', 'status')) {
                $table->enum('status', ['aktif', 'non-aktif'])->default('aktif')->after('jumlah');
            }

            if (Schema::hasColumn('stock_perangkat', 'kondisi')) {
                $table->enum('kondisi', ['baru', 'normal'])->default('baru')->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_perangkat', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_perangkat', 'supplai')) {
                $table->string('supplai', 255)->nullable()->after('type_barang');
            }

            if (Schema::hasColumn('stock_perangkat', 'jumlah')) {
                $table->dropColumn('jumlah');
            }

            if (Schema::hasColumn('stock_perangkat', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('stock_perangkat', 'kondisi')) {
                $table->enum('kondisi', ['BAIK', 'RUSAK', 'PERLU SERVICE'])->default('BAIK')->change();
            }
        });
    }
};