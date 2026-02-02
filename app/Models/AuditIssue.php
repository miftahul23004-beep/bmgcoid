<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditIssue extends Model
{
    protected $fillable = [
        'audit_result_id',
        'type',
        'severity',
        'category',
        'title',
        'description',
        'suggestion',
        'element',
        'impact_score',
        'status',
        'fixed_at',
    ];

    protected $casts = [
        'impact_score' => 'decimal:2',
        'fixed_at' => 'datetime',
    ];

    public function auditResult(): BelongsTo
    {
        return $this->belongsTo(AuditResult::class);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeWarning($query)
    {
        return $query->where('severity', 'warning');
    }

    public function scopeInfo($query)
    {
        return $query->where('severity', 'info');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeFixed($query)
    {
        return $query->where('status', 'fixed');
    }

    public function scopeIgnored($query)
    {
        return $query->where('status', 'ignored');
    }

    public function markAsFixed(): void
    {
        $this->update([
            'status' => 'fixed',
            'fixed_at' => now(),
        ]);
    }

    public function ignore(): void
    {
        $this->update(['status' => 'ignored']);
    }

    public function wontFix(): void
    {
        $this->update(['status' => 'wont_fix']);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => 'open',
            'fixed_at' => null,
        ]);
    }
}
