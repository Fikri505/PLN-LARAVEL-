<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_servers', function (Blueprint $table) {
            $table->id();
            $table->string('ind', 100)->nullable()->index('idx_ind');
            $table->string('fungsi_server', 255)->nullable();
            $table->string('ip', 50)->nullable()->index('idx_ip');
            $table->text('detail')->nullable();
            $table->string('merk', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('system_operasi', 100)->nullable();
            $table->string('processor_merk', 100)->nullable();
            $table->string('processor_type', 100)->nullable();
            $table->string('processor_kecepatan', 50)->nullable();
            $table->string('processor_keping', 50)->nullable();
            $table->string('processor_core', 50)->nullable();
            $table->string('ram_jenis', 50)->nullable();
            $table->string('ram_kapasitas', 50)->nullable();
            $table->string('ram_jumlah_keping', 50)->nullable();
            $table->string('storage_jenis', 50)->nullable();
            $table->string('storage_jumlah', 50)->nullable();
            $table->string('storage_kapasitas_total', 100)->nullable();
            $table->text('keterangan_tambahan')->nullable();
            $table->string('server_fisik', 100)->nullable();
            $table->string('gambar', 255)->nullable();
            $table->enum('status_server', ['HIDUP', 'MATI'])->default('HIDUP');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_servers');
    }
};
