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
    Schema::create('transport_mortality', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
        $table->string('transport_type');
        $table->integer('quantity');
        $table->text('cause');
        $table->text('notes')->nullable();
        $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
        $table->enum('status', ['reported', 'investigating', 'resolved'])->default('reported');
        $table->timestamp('resolved_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_mortality');
    }
};
