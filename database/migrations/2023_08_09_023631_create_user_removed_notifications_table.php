<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_removed_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remover_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('user_notifications_removed_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_removed_notifications');
    }
};
