<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'action', 'model', 'model_id',
        'old_values', 'new_values', 'ip_address', 'user_agent',
    ];

    protected $casts = ['old_values' => 'array', 'new_values' => 'array'];

    public function user() { return $this->belongsTo(User::class); }

    public static function record(string $action, ?string $model = null, ?int $modelId = null, array $old = [], array $new = []): void
    {
        static::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'model'      => $model,
            'model_id'   => $modelId,
            'old_values' => $old ?: null,
            'new_values' => $new ?: null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
