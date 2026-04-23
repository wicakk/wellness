<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAccessLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'education_content_id', 'accessed_at', 'duration_seconds'];

    protected $casts = ['accessed_at' => 'datetime'];

    public function user()    { return $this->belongsTo(User::class); }
    public function content() { return $this->belongsTo(EducationContent::class, 'education_content_id'); }
}
