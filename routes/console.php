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

// Cleanup old logs weekly (performance logs 30d, audit 90d, sessions 7d)
Schedule::command('logs:prune')
    ->weekly()
    ->sundays()
    ->at('03:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/prune.log'));
