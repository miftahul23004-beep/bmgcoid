<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Define all scheduled tasks for the application here.
| Run `php artisan schedule:work` for local testing.
| For production: Add `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1` to cron
|
*/

// Run scheduled audits every hour
Schedule::command('audit:scheduled')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/audit-scheduled.log'));

// Run full site audit daily at 2 AM
Schedule::command('audit:run', ['--type' => 'all'])
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/audit-daily.log'));

// Cleanup old performance logs weekly (keep 30 days)
Schedule::call(function () {
    \App\Models\PerformanceLog::where('created_at', '<', now()->subDays(30))->delete();
})->weekly()->sundays()->at('03:00');

// Cleanup old audit results monthly (keep 90 days)
Schedule::call(function () {
    $oldResults = \App\Models\AuditResult::where('created_at', '<', now()->subDays(90))->get();
    foreach ($oldResults as $result) {
        $result->issues()->delete();
        $result->delete();
    }
})->monthlyOn(1, '03:30');
