<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskThreshold extends Model
{
    protected $fillable = ['questionnaire_id', 'level', 'score_min', 'score_max', 'color_code', 'description'];

    public function questionnaire() { return $this->belongsTo(Questionnaire::class); }

    public static function classify(int $score, int $questionnaireId): string
    {
        $threshold = static::where('questionnaire_id', $questionnaireId)
            ->where('score_min', '<=', $score)
            ->where('score_max', '>=', $score)
            ->first();

        return $threshold?->level ?? 'normal';
    }
}
