<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneOldLogs extends Command
{
    protected $signature = 'logs:prune {--days=30 : Days to keep}';
    protected $description = 'Prune old performance logs and audit results';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        // Prune performance_logs
        $perfDeleted = DB::table('performance_logs')
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Deleted {$perfDeleted} performance logs older than {$days} days.");

        // Prune activity_log (Spatie)
        $activityDeleted = DB::table('activity_log')
            ->where('created_at', '<', now()->subDays(90))
            ->delete();

        $this->info("Deleted {$activityDeleted} activity logs older than 90 days.");

        // Prune old audit results + issues (keep 90 days)
        $oldAuditIds = DB::table('audit_results')
            ->where('created_at', '<', now()->subDays(90))
            ->pluck('id');

        if ($oldAuditIds->isNotEmpty()) {
            $issuesDeleted = DB::table('audit_issues')
                ->whereIn('audit_result_id', $oldAuditIds)
                ->delete();
            $auditsDeleted = DB::table('audit_results')
                ->whereIn('id', $oldAuditIds)
                ->delete();

            $this->info("Deleted {$auditsDeleted} audit results and {$issuesDeleted} audit issues older than 90 days.");
        } else {
            $this->info('No old audit results to prune.');
        }

        // Prune old sessions (keep 7 days)
        if (\Schema::hasTable('sessions')) {
            $sessionsDeleted = DB::table('sessions')
                ->where('last_activity', '<', now()->subDays(7)->timestamp)
                ->delete();

            $this->info("Deleted {$sessionsDeleted} expired sessions older than 7 days.");
        }

        $this->info('Log pruning complete.');
        return self::SUCCESS;
    }
}
