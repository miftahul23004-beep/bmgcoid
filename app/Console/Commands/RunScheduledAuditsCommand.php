<?php

namespace App\Console\Commands;

use App\Models\ScheduledAudit;
use App\Services\Audit\PerformanceAuditService;
use App\Services\Audit\SeoAuditService;
use App\Services\Audit\SecurityAuditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunScheduledAuditsCommand extends Command
{
    protected $signature = 'audit:scheduled';

    protected $description = 'Run all scheduled audits that are due';

    public function handle(): int
    {
        $audits = ScheduledAudit::due()->get();

        if ($audits->isEmpty()) {
            $this->info('No scheduled audits due at this time.');
            return self::SUCCESS;
        }

        $this->info("Running {$audits->count()} scheduled audit(s)...");

        foreach ($audits as $audit) {
            $this->processScheduledAudit($audit);
        }

        $this->info('All scheduled audits completed.');
        return self::SUCCESS;
    }

    protected function processScheduledAudit(ScheduledAudit $audit): void
    {
        $this->line("Processing: {$audit->name}");

        $urls = $audit->urls ?? [config('app.url')];

        foreach ($urls as $url) {
            try {
                $this->line("  Auditing: {$url}");

                if ($audit->include_performance) {
                    app(PerformanceAuditService::class)->audit($url, 'scheduled');
                    $this->line("    ✓ Performance audit complete");
                }

                if ($audit->include_seo) {
                    app(SeoAuditService::class)->audit($url, 'scheduled');
                    $this->line("    ✓ SEO audit complete");
                }

                // Always run security audit
                app(SecurityAuditService::class)->audit($url);
                $this->line("    ✓ Security audit complete");

            } catch (\Exception $e) {
                $this->error("    ✗ Audit failed: {$e->getMessage()}");
                Log::error('Scheduled audit failed', [
                    'audit_id' => $audit->id,
                    'url' => $url,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Mark as run and calculate next run time
        $audit->markAsRun();
        $this->line("  Next run: {$audit->next_run_at->format('Y-m-d H:i:s')}");
    }
}
