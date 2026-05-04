<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_maintenance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('server_id');
            $table->string('waktu_pemeliharaan', 255);
            $table->text('temuan');
            $table->string('dicek_oleh', 255);
            $table->enum('kondisi', ['HIDUP', 'MATI']);
            $table->enum('status', ['PROBLEM', 'AMAN']);
            $table->string('gambar', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('server_id', 'idx_server_id');
            $table->index('created_at', 'idx_created_at');

            $table->foreign('server_id')->references('id')->on('data_servers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_maintenance');
    }
};
