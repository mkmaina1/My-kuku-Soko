<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('mortality_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
        $table->enum('report_type', ['complaint', 'report', 'suggestion', 'other']);
        $table->string('title');
        $table->text('description');
        $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
        $table->enum('status', ['open', 'investigating', 'resolved', 'closed'])->default('open');
        $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamp('resolved_at')->nullable();
        $table->text('resolution_notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mortality_reports');
    }
};
