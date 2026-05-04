<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 30)->unique('idx_schedules_transaction_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('pic_acara', 150);
            $table->string('nama_acara', 255);
            $table->text('pic_it_support')->nullable();
            $table->string('meeting_room', 100);
            $table->string('pelaksanaan', 20);
            $table->string('standby_status', 20);
            $table->text('kebutuhan_detail')->nullable();
            $table->string('tindak_lanjut', 20);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
