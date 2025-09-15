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
        Schema::create('payhero_responses', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->integer('result_code')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('external_reference')->nullable();
            $table->string('status')->nullable();
            $table->text('result_desc')->nullable();
            $table->decimal('service_wallet_balance', 15, 2)->nullable();
            $table->decimal('payment_wallet_balance', 15, 2)->nullable();
            $table->integer('channel_id')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payhero_responses');
    }
};
