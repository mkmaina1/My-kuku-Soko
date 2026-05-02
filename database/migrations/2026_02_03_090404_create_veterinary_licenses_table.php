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
    Schema::create('veterinary_licenses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('document_type');
        $table->string('document_number')->nullable();
        $table->date('issue_date')->nullable();
        $table->date('expiry_date')->nullable();
        $table->string('issuing_authority')->nullable();
        $table->string('document_path');
        $table->text('notes')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->boolean('is_pending')->default(true);
        $table->foreignId('verified_by')->nullable()->constrained('users');
        $table->timestamp('verified_at')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->timestamps();

        $table->index(['user_id', 'document_type']);
        $table->index('expiry_date');
        $table->index('is_verified');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veterinary_licenses');
    }
};
