<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Pro
            $table->string('slug')->unique(); // basic, pro
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('duration')->default('monthly'); // monthly, yearly
            $table->json('features')->nullable(); // Store features as JSON
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
};
