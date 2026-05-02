<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mpesa_payments', function (Blueprint $table) {
            $table->id();
            $table->string('checkout_request_id')->unique();
            $table->string('merchant_request_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('phone_number');
            $table->decimal('amount', 10, 2);
            $table->string('account_reference');
            $table->string('transaction_desc');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('mpesa_receipt_number')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->text('result_desc')->nullable();
            $table->json('callback_data')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('checkout_request_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mpesa_payments');
    }
};
