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
            $table->string('transaction_id')->nullable();
            $table->string('status')->nullable();
            $table->string('phone_number')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('reference')->nullable();
            $table->json('raw_response')->nullable(); // store full payload
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
