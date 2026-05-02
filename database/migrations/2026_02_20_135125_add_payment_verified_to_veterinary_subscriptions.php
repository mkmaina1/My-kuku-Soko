<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('veterinary_subscriptions', function (Blueprint $table) {
            $table->boolean('payment_verified')->default(false)->after('status');
            $table->timestamp('verified_at')->nullable()->after('payment_verified');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
        });
    }

    public function down()
    {
        Schema::table('veterinary_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['payment_verified', 'verified_at', 'verified_by']);
        });
    }
};
