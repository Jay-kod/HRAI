<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WearableSession extends Model
{
    /** @use HasFactory<\Database\Factories\WearableSessionFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'source', 'session_date', 'average_heart_rate', 'steps', 'sleep_hours', 'raw_data'];

    protected function casts(): array
    {
        return ['session_date' => 'date', 'sleep_hours' => 'decimal:2', 'raw_data' => 'array'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
