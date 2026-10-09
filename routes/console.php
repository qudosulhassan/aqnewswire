<?php

use App\Services\TrendingService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('trending:recalculate', function () {
    $count = app(TrendingService::class)->recalculateScores();
    $this->info("Recalculated trending gravity scores for {$count} articles.");
})->purpose('Recalculate engagement gravity trending scores for published articles');

// Scheduled Recurring Jobs
Schedule::command('trending:recalculate')->hourly();
Schedule::command('auth:clear-resets')->everyFifteenMinutes();
