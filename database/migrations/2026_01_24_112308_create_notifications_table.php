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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type'); // 'agent', 'farmer', 'supplier', 'veterinary', 'admin'
            $table->string('type'); // 'email', 'sms', 'order', 'commission', 'target', 'marketplace', 'system'
            $table->string('title');
            $table->text('message');
            $table->boolean('read')->default(false);
            $table->json('data')->nullable(); // Additional data in JSON format
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'user_type']);
            $table->index(['user_id', 'user_type', 'read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
