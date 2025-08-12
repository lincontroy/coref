<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userDetails(): HasOne
    {
        return $this->hasOne(UserDetails::class);
    }

    /**
     * Get or create user details for the current user.
     */
    public function getOrCreateUserDetails(): UserDetails
    {
        return $this->userDetails ?: $this->userDetails()->create(['user_id' => $this->id]);
    }

    /**
     * Check if user has completed their profile
     */
    public function hasCompleteProfile(): bool
    {
        $details = $this->userDetails;
        return $details && 
               $details->employment_details_completed && 
               $details->financial_details_completed;
    }

    /**
     * Get profile completion percentage
     */
    public function getProfileCompletionAttribute(): int
    {
        return $this->userDetails ? $this->userDetails->profile_completion_percentage : 0;
    }

    /**
     * Get employment status with badge
     */
    public function getEmploymentStatusAttribute(): string
    {
        return $this->userDetails ? $this->userDetails->employment_status : 'Not Set';
    }

    /**
     * Get formatted monthly income
     */
    public function getFormattedIncomeAttribute(): string
    {
        return $this->userDetails ? $this->userDetails->formatted_monthly_income : 'KES 0';
    }

    public function loans()
{
    return $this->hasMany(Loans::class);
}

public function latePayments()
{
    return $this->hasMany(Payment::class)->where('status', 'late');
}
}
