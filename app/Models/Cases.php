<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cases extends Model
{
    use SoftDeletes;

    protected $table = 'cases';

    protected $fillable = [
        'user_id', 'screening_id', 'assigned_to', 'priority',
        'status', 'notes', 'target_completion', 'closed_at',
    ];

    protected $casts = ['target_completion' => 'date', 'closed_at' => 'datetime'];

    public function user()          { return $this->belongsTo(User::class); }
    public function screening()     { return $this->belongsTo(Screening::class); }
    public function assignedTo()    { return $this->belongsTo(User::class, 'assigned_to'); }
    public function interventions() { return $this->hasMany(Intervention::class, 'case_id'); }
}
