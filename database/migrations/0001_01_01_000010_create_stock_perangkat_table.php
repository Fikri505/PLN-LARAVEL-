<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_perangkat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 255);
            $table->string('type_barang', 255)->nullable();
            $table->string('supplai', 255)->nullable();
            $table->enum('kondisi', ['BAIK', 'RUSAK', 'PERLU SERVICE'])->default('BAIK');
            $table->text('keterangan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_perangkat');
    }
};
