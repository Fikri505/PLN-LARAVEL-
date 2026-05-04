<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('it_support_jateng', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 255);
            $table->string('email', 255)->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('penempatan', 255)->nullable();
            $table->string('ops_sti', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_support_jateng');
    }
};
