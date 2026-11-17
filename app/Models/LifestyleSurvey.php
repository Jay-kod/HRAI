<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifestyleSurvey extends Model
{
    /** @use HasFactory<\Database\Factories\LifestyleSurveyFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'biometric_data_id', 'smoker', 'heavy_alcohol', 'phys_activity', 'fruit', 'veggies', 'sleep_hours', 'stress_level', 'family_history', 'stroke', 'diagnosed_conditions'];

    protected function casts(): array
    {
        return ['smoker' => 'boolean', 'heavy_alcohol' => 'boolean', 'phys_activity' => 'boolean', 'fruit' => 'boolean', 'veggies' => 'boolean', 'family_history' => 'boolean', 'stroke' => 'boolean', 'diagnosed_conditions' => 'array'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function biometricData() { return $this->belongsTo(BiometricData::class); }
}
