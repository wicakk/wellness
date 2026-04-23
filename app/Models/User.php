<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id', 'name', 'email', 'nip', 'phone', 'unit', 'jabatan',
        'gender', 'tanggal_lahir', 'address', 'avatar',
        'password', 'theme_preference', 'last_login_at', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'tanggal_lahir'     => 'date',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function cases()
    {
        return $this->hasMany(Cases::class);
    }

    public function assignedCases()
    {
        return $this->hasMany(Cases::class, 'assigned_to');
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'performed_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function educationAccessLogs()
    {
        return $this->hasMany(EducationAccessLog::class);
    }

    // ── Role Helpers ──────────────────────────────────────────────

    public function hasRole(string $roleName): bool
    {
        return $this->role->name === $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isWellness(): bool
    {
        return $this->hasRole('wellness_warrior');
    }

    public function isPsikolog(): bool
    {
        return $this->hasRole('psikolog');
    }

    public function isPegawai(): bool
    {
        return $this->hasRole('pegawai');
    }

    public function canManageCases(): bool
    {
        return in_array($this->role->name, ['admin', 'wellness_warrior', 'psikolog']);
    }

    // ── Accessors ─────────────────────────────────────────────────

    public function getLatestScreeningAttribute()
    {
        return $this->screenings()->where('status', 'completed')->latest('completed_at')->first();
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
