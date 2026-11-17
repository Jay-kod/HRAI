<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsabilityFeedback extends Model
{
    /** @use HasFactory<\Database\Factories\UsabilityFeedbackFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'ease_of_use', 'usefulness', 'confidence', 'intention', 'comment', 'submitted_at'];

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
