<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationContent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by', 'title', 'description', 'type', 'category',
        'file_path', 'thumbnail', 'external_url', 'view_count', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function accessLogs() { return $this->hasMany(EducationAccessLog::class); }
}
