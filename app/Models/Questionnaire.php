<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function riskThresholds()
    {
        return $this->hasMany(RiskThreshold::class);
    }
}
