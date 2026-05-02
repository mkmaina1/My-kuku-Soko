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
    Schema::table('orders', function (Blueprint $table) {
        $table->boolean('mortality_expectation_flag')->default(false);
        $table->enum('mortality_risk_level', ['low', 'medium', 'high'])->nullable();
        $table->boolean('mortality_resolved')->default(false);
        $table->text('mortality_notes')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
