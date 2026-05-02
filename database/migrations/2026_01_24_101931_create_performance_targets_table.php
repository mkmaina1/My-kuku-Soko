<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('performance_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('target_type', ['sales', 'orders', 'farmers', 'completion_rate', 'revenue', 'custom']);
            $table->decimal('target_value', 15, 2);
            $table->decimal('current_value', 15, 2)->default(0);
            $table->enum('period', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly']);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'cancelled', 'failed'])->default('active');
            $table->text('description')->nullable();
            $table->json('metrics')->nullable(); // For storing custom metrics
            $table->timestamps();

            // Indexes
            $table->index(['agent_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_targets');
    }
};
