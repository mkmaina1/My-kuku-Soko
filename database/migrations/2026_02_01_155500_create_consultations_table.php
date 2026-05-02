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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->string('consultation_number')->unique();
            $table->enum('consultation_type', ['in_person', 'telemedicine', 'emergency', 'follow_up']);
            $table->enum('poultry_type', ['broilers', 'layers', 'kienyeji', 'breeding', 'other']);
            $table->integer('flock_size')->nullable();
            $table->integer('age_weeks')->nullable();
            $table->enum('consultation_status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'normal', 'high', 'emergency'])->default('normal');

            // Symptoms & Observations
            $table->text('symptoms')->nullable();
            $table->text('observations')->nullable();
            $table->decimal('mortality_rate', 5, 2)->nullable()->comment('Daily mortality rate %');
            $table->decimal('feed_intake', 8, 2)->nullable()->comment('Daily feed intake in kg');
            $table->decimal('water_intake', 8, 2)->nullable()->comment('Daily water intake in liters');

            // Diagnosis
            $table->text('diagnosis')->nullable();
            $table->text('differential_diagnosis')->nullable();

            // Treatment
            $table->text('treatment_plan')->nullable();
            $table->text('medications')->nullable();
            $table->text('vaccinations')->nullable();
            $table->text('biosecurity_measures')->nullable();

            // Recommendations
            $table->text('feeding_recommendations')->nullable();
            $table->text('management_recommendations')->nullable();
            $table->text('follow_up_instructions')->nullable();

            // Appointment Details
            $table->timestamp('appointment_date')->nullable();
            $table->timestamp('consultation_date')->nullable();
            $table->timestamp('follow_up_date')->nullable();

            // Location
            $table->string('location')->nullable();
            $table->string('farm_name')->nullable();

            // Files/Images
            $table->json('attachments')->nullable()->comment('JSON array of file paths');

            // Prescription
            $table->boolean('prescription_issued')->default(false);
            $table->text('prescription_notes')->nullable();

            // Payment
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'waived'])->default('pending');

            // Ratings & Feedback
            $table->integer('rating')->nullable()->comment('1-5 stars');
            $table->text('farmer_feedback')->nullable();
            $table->text('veterinarian_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('consultation_number');
            $table->index('consultation_status');
            $table->index('priority');
            $table->index('appointment_date');
            $table->index(['farmer_id', 'consultation_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
