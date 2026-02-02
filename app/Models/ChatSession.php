<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatSession extends Model
{
    protected $fillable = [
        'session_token',
        'operator_id',
        'assigned_to',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'visitor_ip',
        'visitor_user_agent',
        'page_url',
        'status',
        'handler',
        'message_count',
        'rating',
        'feedback',
        'started_at',
        'last_message_at',
        'closed_at',
        'ended_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'started_at' => 'datetime',
        'last_message_at' => 'datetime',
        'closed_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function assignedOperator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }

    public function scopeHandledByAi($query)
    {
        return $query->where('handler', 'ai');
    }

    public function scopeHandledByOperator($query)
    {
        return $query->where('handler', 'operator');
    }

    public function start(): void
    {
        $this->update([
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    public function close(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    public function assignOperator(User $operator): void
    {
        $this->update([
            'operator_id' => $operator->id,
            'handler' => 'operator',
        ]);
    }

    public function incrementMessageCount(): void
    {
        $this->increment('message_count');
    }
}
