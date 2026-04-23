<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $fillable = [
        'case_id', 'performed_by', 'type', 'intervention_date',
        'notes', 'outcome', 'follow_up_status', 'next_follow_up',
    ];

    protected $casts = ['intervention_date' => 'date', 'next_follow_up' => 'date'];

    public function case()        { return $this->belongsTo(Cases::class, 'case_id'); }
    public function performedBy() { return $this->belongsTo(User::class, 'performed_by'); }
}
