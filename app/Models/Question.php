<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['questionnaire_id', 'order', 'question_text', 'type', 'options', 'max_score'];

    protected $casts = ['options' => 'array'];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers()
    {
        return $this->hasMany(ScreeningAnswer::class);
    }
}
