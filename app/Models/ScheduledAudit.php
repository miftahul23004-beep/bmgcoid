<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledAudit extends Model
{
    protected $fillable = [
        'name',
        'urls',
        'frequency',
        'time',
        'day_of_week',
        'day_of_month',
        'include_performance',
        'include_seo',
        'is_active',
        'last_run_at',
        'next_run_at',
    ];

    protected $casts = [
        'urls' => 'array',
        'day_of_month' => 'integer',
        'include_performance' => 'boolean',
        'include_seo' => 'boolean',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDue($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('next_run_at')
                    ->orWhere('next_run_at', '<=', now());
            });
    }

    public function scopeFrequency($query, string $frequency)
    {
        return $query->where('frequency', $frequency);
    }

    public function markAsRun(): void
    {
        $this->update([
            'last_run_at' => now(),
            'next_run_at' => $this->calculateNextRun(),
        ]);
    }

    public function calculateNextRun(): \DateTime
    {
        $now = now();

        return match ($this->frequency) {
            'hourly' => $now->addHour(),
            'daily' => $now->addDay()->setTimeFromTimeString($this->time ?? '00:00'),
            'weekly' => $now->next($this->day_of_week ?? 'monday')->setTimeFromTimeString($this->time ?? '00:00'),
            'monthly' => $now->addMonth()->day($this->day_of_month ?? 1)->setTimeFromTimeString($this->time ?? '00:00'),
            default => $now->addDay(),
        };
    }

    public function getAuditType(): string
    {
        if ($this->include_performance && $this->include_seo) {
            return 'both';
        }
        if ($this->include_performance) {
            return 'performance';
        }
        return 'seo';
    }
}
