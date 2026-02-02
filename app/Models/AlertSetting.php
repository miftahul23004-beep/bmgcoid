<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertSetting extends Model
{
    protected $fillable = [
        'user_id',
        'alert_type',
        'conditions',
        'channels',
        'is_active',
        'last_triggered_at',
    ];

    protected $casts = [
        'conditions' => 'array',
        'channels' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeWithChannel($query, string $channel)
    {
        return $query->whereJsonContains('channels', $channel);
    }

    public function markAsTriggered(): void
    {
        $this->update(['last_triggered_at' => now()]);
    }

    public function hasChannel(string $channel): bool
    {
        return in_array($channel, $this->channels ?? []);
    }

    public function shouldNotifyViaEmail(): bool
    {
        return $this->hasChannel('email');
    }

    public function shouldNotifyViaSlack(): bool
    {
        return $this->hasChannel('slack');
    }

    public function shouldNotifyViaDatabase(): bool
    {
        return $this->hasChannel('database');
    }
}
