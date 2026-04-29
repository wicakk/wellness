<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'location',
        'capacity',
        'type',
        'description',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity'  => 'integer',
    ];

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke jadwal (jika Schedule punya room_id)
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Scope: hanya ruangan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Label tipe
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'konsultasi' => 'Konsultasi',
            'grup'       => 'Grup / Sesi Bersama',
            'relaksasi'  => 'Relaksasi',
            default      => 'Lainnya',
        };
    }
}