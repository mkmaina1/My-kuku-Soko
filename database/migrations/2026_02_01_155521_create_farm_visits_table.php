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
        Schema::create('farm_visits', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('visit_number')->unique();
            $table->enum('visit_type', ['routine', 'emergency', 'follow_up', 'consultation', 'vaccination', 'inspection']);
            $table->enum('priority', ['low', 'normal', 'high', 'emergency'])->default('normal');
            $table->enum('visit_status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');

            // Farm Details
            $table->string('farm_name');
            $table->string('location');
            $table->string('county')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('ward')->nullable();
            $table->text('farm_address')->nullable();
            $table->decimal('gps_latitude', 10, 8)->nullable();
            $table->decimal('gps_longitude', 11, 8)->nullable();

            // Poultry Details
            $table->enum('poultry_type', ['broilers', 'layers', 'kienyeji', 'breeding', 'mixed', 'other'])->default('layers');
            $table->integer('total_flock_size')->nullable();
            $table->integer('affected_flock_size')->nullable();
            $table->integer('age_weeks')->nullable();
            $table->string('housing_type')->nullable()->comment('Deep litter, Battery cages, Free range, etc.');

            // Visit Details
            $table->text('visit_purpose');
            $table->text('specific_issues')->nullable()->comment('Specific issues to address');
            $table->timestamp('scheduled_date');
            $table->timestamp('actual_start_time')->nullable();
            $table->timestamp('actual_end_time')->nullable();
            $table->integer('duration_minutes')->nullable()->comment('Actual visit duration');
            $table->decimal('distance_km', 8, 2)->nullable()->comment('Distance traveled');
            $table->decimal('transport_cost', 10, 2)->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();

            // Observations & Findings
            $table->text('observations')->nullable();
            $table->text('issues_found')->nullable();
            $table->decimal('mortality_rate', 5, 2)->nullable()->comment('Current mortality rate %');
            $table->decimal('feed_intake', 8, 2)->nullable()->comment('Daily feed intake kg');
            $table->decimal('water_intake', 8, 2)->nullable()->comment('Daily water intake liters');
            $table->decimal('egg_production', 5, 2)->nullable()->comment('For layers: egg production %');
            $table->decimal('feed_conversion_ratio', 5, 2)->nullable()->comment('FCR');

            // Diagnosis & Recommendations
            $table->text('diagnosis')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('treatment_administered')->nullable();
            $table->text('vaccinations_administered')->nullable();
            $table->text('biosecurity_assessment')->nullable();
            $table->text('management_advice')->nullable();
            $table->text('follow_up_plan')->nullable();

            // Emergency Details
            $table->boolean('is_emergency')->default(false);
            $table->text('emergency_details')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();

            // Report & Documentation
            $table->text('visit_summary')->nullable();
            $table->json('photos')->nullable()->comment('JSON array of photo paths');
            $table->json('documents')->nullable()->comment('JSON array of document paths');
            $table->boolean('report_generated')->default(false);
            $table->timestamp('report_generated_at')->nullable();

            // Follow-up
            $table->timestamp('follow_up_date')->nullable();
            $table->text('follow_up_notes')->nullable();

            // Payment & Billing
            $table->enum('payment_status', ['pending', 'paid', 'waived', 'partial'])->default('pending');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('balance', 10, 2)->nullable();

            // Feedback & Rating
            $table->integer('farmer_rating')->nullable()->comment('1-5 stars');
            $table->text('farmer_feedback')->nullable();
            $table->text('veterinarian_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('visit_number');
            $table->index('visit_status');
            $table->index('priority');
            $table->index('scheduled_date');
            $table->index(['veterinarian_id', 'visit_status']);
            $table->index(['farmer_id', 'visit_status']);
            $table->index('is_emergency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_visits');
    }
};
