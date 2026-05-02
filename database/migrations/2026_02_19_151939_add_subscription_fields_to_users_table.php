<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_active_subscription')->default(false);
            $table->string('subscription_plan')->nullable(); // basic, pro
            $table->timestamp('subscription_expires_at')->nullable();
            $table->json('subscription_features')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['has_active_subscription', 'subscription_plan', 'subscription_expires_at', 'subscription_features']);
        });
    }
};
