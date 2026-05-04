<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zoom_units', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120)->unique('uq_zoom_unit_name');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('zoom_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 180)->unique('uq_zoom_link_email');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('zoom_bookings', function (Blueprint $table) {
            $table->id();
            $table->date('booking_date');
            $table->string('booking_time', 20);
            $table->string('zoom_link', 50);
            $table->text('keterangan')->nullable();
            $table->string('unit', 50)->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->enum('kondisi', ['KOSONG', 'DIPAKAI'])->default('DIPAKAI');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('booking_date', 'idx_date');
            $table->index('kondisi', 'idx_kondisi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoom_bookings');
        Schema::dropIfExists('zoom_links');
        Schema::dropIfExists('zoom_units');
    }
};
