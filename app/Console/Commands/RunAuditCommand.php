<?php

namespace App\Console\Commands;

use App\Services\Audit\PerformanceAuditService;
use App\Services\Audit\SeoAuditService;
use App\Services\Audit\SecurityAuditService;
use Illuminate\Console\Command;

class RunAuditCommand extends Command
{
    protected $signature = 'audit:run 
                            {url? : The URL to audit (defaults to APP_URL)}
                            {--type=all : Type of audit (performance, seo, security, all)}
                            {--page-type=page : Page type for categorization}';

    protected $description = 'Run performance, SEO, and security audits on a URL';

    public function handle(): int
    {
        $url = $this->argument('url') ?? config('app.url');
        $type = $this->option('type');
        $pageType = $this->option('page-type');

        $this->info("🔍 Running {$type} audit on: {$url}");
        $this->newLine();

        $results = [];

        try {
            if ($type === 'all' || $type === 'performance') {
                $this->runPerformanceAudit($url, $pageType, $results);
            }

            if ($type === 'all' || $type === 'seo') {
                $this->runSeoAudit($url, $pageType, $results);
            }

            if ($type === 'all' || $type === 'security') {
                $this->runSecurityAudit($url, $results);
            }

            // Summary
            $this->displaySummary($results);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Audit failed: {$e->getMessage()}");
            return self::FAILURE;
        }
    }

    protected function runPerformanceAudit(string $url, string $pageType, array &$results): void
    {
        $this->info('📊 Performance Audit');
        $this->line('─────────────────────');
        
        $service = app(PerformanceAuditService::class);
        $result = $service->audit($url, $pageType);
        
        $results['performance'] = $result;

        // Display results
        $score = $result->performance_score;
        $scoreColor = $this->getScoreColor($score);
        
        $this->line("  Performance Score: <{$scoreColor}>{$score}/100</>");
        
        if ($result->fcp) {
            $this->line("  FCP: {$result->fcp}ms");
        }
        if ($result->lcp) {
            $this->line("  LCP: {$result->lcp}ms");
        }
        if ($result->cls !== null) {
            $this->line("  CLS: {$result->cls}");
        }
        if ($result->tbt) {
            $this->line("  TBT: {$result->tbt}ms");
        }
        if ($result->load_time) {
            $this->line("  Load Time: {$result->load_time}ms");
        }
        if ($result->page_size) {
            $sizeKb = round($result->page_size / 1024, 1);
            $this->line("  Page Size: {$sizeKb}KB");
        }

        // Display issues
        $this->displayIssues($result->issues);
        $this->newLine();
    }

    protected function runSeoAudit(string $url, string $pageType, array &$results): void
    {
        $this->info('🔎 SEO Audit');
        $this->line('─────────────────────');
        
        $service = app(SeoAuditService::class);
        $result = $service->audit($url, $pageType);
        
        $results['seo'] = $result;

        // Display results
        $score = $result->seo_score;
        $scoreColor = $this->getScoreColor($score);
        
        $this->line("  SEO Score: <{$scoreColor}>{$score}/100</>");
        
        $rawData = $result->raw_data ?? [];
        
        if (isset($rawData['meta']['title'])) {
            $titleLen = $rawData['meta']['title_length'] ?? 0;
            $this->line("  Title: {$rawData['meta']['title']} ({$titleLen} chars)");
        }
        
        if (isset($rawData['content']['word_count'])) {
            $this->line("  Word Count: {$rawData['content']['word_count']}");
        }
        
        if (isset($rawData['images']['total'])) {
            $total = $rawData['images']['total'];
            $missingAlt = $rawData['images']['missing_alt'] ?? 0;
            $this->line("  Images: {$total} ({$missingAlt} missing alt)");
        }
        
        if (isset($rawData['links'])) {
            $internal = $rawData['links']['internal'] ?? 0;
            $external = $rawData['links']['external'] ?? 0;
            $this->line("  Links: {$internal} internal, {$external} external");
        }

        // Display issues
        $this->displayIssues($result->issues);
        $this->newLine();
    }

    protected function runSecurityAudit(string $url, array &$results): void
    {
        $this->info('🔒 Security Audit');
        $this->line('─────────────────────');
        
        $service = app(SecurityAuditService::class);
        $result = $service->audit($url);
        
        $results['security'] = $result;

        // Display results
        $score = $result->best_practices_score;
        $scoreColor = $this->getScoreColor($score);
        
        $this->line("  Security Score: <{$scoreColor}>{$score}/100</>");
        
        $rawData = $result->raw_data ?? [];
        
        if (isset($rawData['headers'])) {
            $present = count($rawData['headers']['present'] ?? []);
            $missing = count($rawData['headers']['missing'] ?? []);
            $this->line("  Security Headers: {$present} present, {$missing} missing");
        }
        
        if (isset($rawData['ssl']['is_https'])) {
            $https = $rawData['ssl']['is_https'] ? '✓ Enabled' : '✗ Disabled';
            $this->line("  HTTPS: {$https}");
        }
        
        if (isset($rawData['ssl']['certificate']['days_until_expiry'])) {
            $days = $rawData['ssl']['certificate']['days_until_expiry'];
            $this->line("  SSL Certificate: Expires in {$days} days");
        }

        if (isset($rawData['exposure']['exposed']) && !empty($rawData['exposure']['exposed'])) {
            $exposed = implode(', ', $rawData['exposure']['exposed']);
            $this->line("  <fg=red>⚠ Exposed files: {$exposed}</>");
        }

        // Display issues
        $this->displayIssues($result->issues);
        $this->newLine();
    }

    protected function displayIssues($issues): void
    {
        if ($issues->isEmpty()) {
            $this->line('  <fg=green>✓ No issues found</>');
            return;
        }

        $this->newLine();
        $this->line('  Issues:');
        
        $grouped = $issues->groupBy('severity');
        
        foreach (['critical', 'warning', 'info'] as $severity) {
            if (!isset($grouped[$severity])) {
                continue;
            }
            
            $icon = match ($severity) {
                'critical' => '<fg=red>✗</>',
                'warning' => '<fg=yellow>⚠</>',
                'info' => '<fg=blue>ℹ</>',
            };
            
            foreach ($grouped[$severity] as $issue) {
                $this->line("    {$icon} {$issue->title}");
            }
        }
    }

    protected function displaySummary(array $results): void
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('              AUDIT SUMMARY            ');
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        $totalIssues = 0;
        $criticalIssues = 0;

        foreach ($results as $type => $result) {
            $score = match ($type) {
                'performance' => $result->performance_score,
                'seo' => $result->seo_score,
                'security' => $result->best_practices_score,
            };
            
            $scoreColor = $this->getScoreColor($score);
            $label = ucfirst($type);
            
            $this->line("  {$label}: <{$scoreColor}>{$score}/100</>");
            
            $totalIssues += $result->issues->count();
            $criticalIssues += $result->issues->where('severity', 'critical')->count();
        }

        $this->newLine();
        $this->line("  Total Issues: {$totalIssues}");
        
        if ($criticalIssues > 0) {
            $this->line("  <fg=red>Critical Issues: {$criticalIssues}</>");
        }
        
        $this->newLine();
        $this->line('  View detailed results in Filament Admin Panel.');
        $this->newLine();
    }

    protected function getScoreColor(int $score = null): string
    {
        if ($score === null) {
            return 'fg=gray';
        }
        
        return match (true) {
            $score >= 90 => 'fg=green',
            $score >= 50 => 'fg=yellow',
            default => 'fg=red',
        };
    }
}
