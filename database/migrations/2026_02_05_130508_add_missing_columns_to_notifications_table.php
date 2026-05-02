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
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('link')->nullable()->after('data');
            $table->string('icon')->nullable()->after('link');
            $table->string('color')->nullable()->after('icon');
            $table->unsignedBigInteger('created_by')->nullable()->after('color');

            // If you want to rename 'color' to 'badge_color' based on your controller code
            // $table->string('badge_color')->nullable()->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['link', 'icon', 'color', 'created_by']);
        });
    }
};
