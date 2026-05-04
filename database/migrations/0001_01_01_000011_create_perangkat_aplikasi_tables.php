<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master tables for Perangkat Aplikasi
        Schema::create('master_pa_jenis_perangkat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique('uq_pa_nama');
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('master_pa_brand', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique('uq_pa_brand');
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('master_pa_lokasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique('uq_pa_lokasi');
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('master_pa_bidang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique('uq_pa_bidang');
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('master_pa_msb', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique('uq_pa_msb');
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('perangkat_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_perangkat', 255)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('ip', 100)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('server', 255)->nullable();
            $table->string('os', 255)->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->string('bidang', 100)->nullable();
            $table->string('msb_sub_bidang', 100)->nullable();
            $table->string('firmware_patch', 10)->default('⌛');
            $table->string('database_patch', 10)->default('⌛');
            $table->string('network_device_patch', 10)->default('⌛');
            $table->string('application_patch', 10)->default('⌛');
            $table->string('os_patch', 10)->default('⌛');
            $table->string('library_dependency_patch', 10)->default('⌛');
            $table->string('pemilik_aset', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkat_aplikasi');
        Schema::dropIfExists('master_pa_msb');
        Schema::dropIfExists('master_pa_bidang');
        Schema::dropIfExists('master_pa_lokasi');
        Schema::dropIfExists('master_pa_brand');
        Schema::dropIfExists('master_pa_jenis_perangkat');
    }
};
