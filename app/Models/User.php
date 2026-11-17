<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'date_of_birth',
        'gender',
        'contact',
        'active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'role' => Role::class,
            'gender' => Gender::class,
            'contact' => 'encrypted',
            'active' => 'boolean',
        ];
    }

    public function biometricData()
    {
        return $this->hasMany(BiometricData::class);
    }

    public function lifestyleSurveys()
    {
        return $this->hasMany(LifestyleSurvey::class);
    }

    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }

    public function healthGoals()
    {
        return $this->hasMany(HealthGoal::class);
    }

    public function wearableSessions()
    {
        return $this->hasMany(WearableSession::class);
    }

    public function reportShares()
    {
        return $this->hasMany(ReportShare::class);
    }

    public function usabilityFeedback()
    {
        return $this->hasMany(UsabilityFeedback::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isProvider(): bool
    {
        return $this->role === Role::Provider;
    }

    public function hasCompletedHealthProfile(): bool
    {
        return $this->date_of_birth !== null && $this->gender !== null && filled($this->contact);
    }
}
