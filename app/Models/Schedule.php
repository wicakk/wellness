<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'created_by', 'user_id', 'title', 'description', 'type',
        'start_at', 'end_at', 'location', 'meeting_link', 'is_reminder',
    ];

    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime', 'is_reminder' => 'boolean'];

    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function targetUser() { return $this->belongsTo(User::class, 'user_id'); }
}
