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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Employment and Income Details
            $table->enum('employment_status', ['employed', 'self-employed', 'unemployed'])->nullable();
            $table->string('employer_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employment_duration')->nullable(); // e.g., "2 years 3 months"
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->text('other_income_sources')->nullable();
            
            // Financial Information
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('mobile_money_provider')->nullable(); // e.g., M-Pesa, Airtel Money
            $table->string('mobile_money_account')->nullable();
            $table->decimal('total_monthly_income', 15, 2)->nullable();
            $table->text('existing_loans')->nullable();
            
            // Additional fields for completeness
            $table->boolean('employment_details_completed')->default(false);
            $table->boolean('financial_details_completed')->default(false);
            $table->timestamp('employment_details_updated_at')->nullable();
            $table->timestamp('financial_details_updated_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('employment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};