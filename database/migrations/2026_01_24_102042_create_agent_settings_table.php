<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agent_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');

            // Business Information
            $table->string('business_name')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('tax_identification_number')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_website')->nullable();
            $table->text('business_description')->nullable();

            // Notification Settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(true);
            $table->boolean('order_updates')->default(true);
            $table->boolean('commission_alerts')->default(true);
            $table->boolean('target_alerts')->default(true);
            $table->boolean('marketplace_updates')->default(true);

            // Working Hours
            $table->string('working_days')->nullable()->default('Monday-Friday');
            $table->time('working_hours_start')->nullable()->default('08:00:00');
            $table->time('working_hours_end')->nullable()->default('17:00:00');

            // Commission Settings
            $table->decimal('commission_rate', 5, 2)->default(5.00);
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');

            // Additional Settings
            $table->json('preferences')->nullable();

            $table->timestamps();

            // Ensure one setting per agent
            $table->unique('agent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agent_settings');
    }
};
