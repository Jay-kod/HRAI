<?php

namespace App\Models;

use App\Enums\Severity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskRule extends Model
{
    /** @use HasFactory<\Database\Factories\RiskRuleFactory> */
    use HasFactory;

    protected $fillable = ['code', 'label', 'applies_to', 'fact_key', 'operator', 'threshold', 'severity', 'points', 'active'];

    protected function casts(): array
    {
        return ['applies_to' => 'array', 'threshold' => 'decimal:2', 'severity' => Severity::class, 'active' => 'boolean'];
    }
}
