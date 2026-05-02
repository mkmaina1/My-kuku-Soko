<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketplaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->string('product_type'); // chicks, feed, equipment, medicine
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->string('unit'); // each, kg, liter, package
            $table->string('category'); // poultry, livestock, equipment
            $table->string('image')->nullable();
            $table->string('location');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_available')->default(true);
            $table->integer('min_order')->default(1);
            $table->integer('max_order')->nullable();
            $table->json('tags')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_ratings')->default(0);
            $table->integer('views')->default(0);
            $table->integer('orders_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplaces');
    }
};
