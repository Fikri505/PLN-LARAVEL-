<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('page_slug', 50);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'page_slug'], 'unique_user_page');
            $table->index('user_id', 'idx_user_id');
            $table->index('page_slug', 'idx_page_slug');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
