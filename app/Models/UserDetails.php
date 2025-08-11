<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employment_status',
        'employer_name',
        'job_title',
        'employment_duration',
        'monthly_income',
        'other_income_sources',
        'bank_name',
        'bank_account_number',
        'mobile_money_provider',
        'mobile_money_account',
        'total_monthly_income',
        'existing_loans',
        'employment_details_completed',
        'financial_details_completed',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
        'total_monthly_income' => 'decimal:2',
        'employment_details_completed' => 'boolean',
        'financial_details_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEmploymentStatusBadgeAttribute(): string
    {
        return match($this->employment_status) {
            'employed' => '<span class="badge badge-success">Employed</span>',
            'self-employed' => '<span class="badge badge-info">Self-Employed</span>',
            'unemployed' => '<span class="badge badge-danger">Unemployed</span>',
            default => '<span class="badge badge-secondary">Not Set</span>',
        };
    }

    public function getFormattedMonthlyIncomeAttribute(): string
    {
        return $this->monthly_income ? 'KES ' . number_format($this->monthly_income, 2) : 'Not provided';
    }

    public function getFormattedTotalMonthlyIncomeAttribute(): string
    {
        return $this->total_monthly_income ? 'KES ' . number_format($this->total_monthly_income, 2) : 'Not provided';
    }

    public function getProfileCompletionPercentageAttribute(): int
    {
        $fields = [
            'employment_status',
            'employer_name',
            'job_title',
            'employment_duration',
            'monthly_income',
            'bank_account_number',
            'mobile_money_account',
            'total_monthly_income',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    public function isProfileComplete()
{
    return $this->employment_verified && 
           $this->financial_details_completed &&
           $this->documents_uploaded;
}
}

// Add this to your User model (App\Models\User.php)
/*
use Illuminate\Database\Eloquent\Relations\HasOne;

public function userDetails(): HasOne
{
    return $this->hasOne(UserDetails::class);
}

public function getOrCreateUserDetails(): UserDetails
{
    return $this->userDetails ?: $this->userDetails()->create(['user_id' => $this->id]);
}
*/