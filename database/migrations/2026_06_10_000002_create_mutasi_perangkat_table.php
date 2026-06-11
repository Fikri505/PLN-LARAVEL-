<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_perangkat', function (Blueprint $table) {
            // Supaya aman terhadap tipe campuran di DB, kita pilih tipe yang netral
            $table->bigIncrements('id'); // bigint unsigned auto_increment
            $table->unsignedBigInteger('stock_perangkat_id'); // kita pakai di level aplikasi
            $table->unsignedInteger('jumlah');
            $table->enum('kondisi', ['baru', 'normal'])->default('normal');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // SENGAJA: tidak ada foreign key di sini
            // Nanti kalau skema sudah seragam, kita bisa tambahkan FK di migration terpisah.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_perangkat');
    }
};