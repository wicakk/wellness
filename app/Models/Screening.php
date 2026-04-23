<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screening extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'questionnaire_id', 'total_score',
        'risk_level', 'open_notes', 'status', 'completed_at',
    ];

    protected $casts = ['completed_at' => 'datetime'];

    public function user()          { return $this->belongsTo(User::class); }
    public function questionnaire() { return $this->belongsTo(Questionnaire::class); }
    public function answers()       { return $this->hasMany(ScreeningAnswer::class); }
    public function case()          { return $this->hasOne(Cases::class); }

    public function getRiskBadgeClassAttribute(): string
    {
        return match($this->risk_level) {
            'normal' => 'badge-success',
            'ringan' => 'badge-warning',
            'sedang' => 'badge-orange',
            'tinggi' => 'badge-danger',
            default  => 'badge-secondary',
        };
    }

    public function getRiskColorAttribute(): string
    {
        return match($this->risk_level) {
            'normal' => '#22c55e',
            'ringan' => '#eab308',
            'sedang' => '#f97316',
            'tinggi' => '#ef4444',
            default  => '#94a3b8',
        };
    }
}
