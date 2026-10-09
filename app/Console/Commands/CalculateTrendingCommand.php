<?php

namespace App\Console\Commands;

use App\Services\TrendingService;
use Illuminate\Console\Command;

class CalculateTrendingCommand extends Command
{
    protected $signature = 'trending:calculate';
    protected $description = 'Recalculate trending engagement scores for all recently published articles';

    public function handle(TrendingService $trendingService): int
    {
        $this->info('Calculating article trending scores with engagement gravity decay...');
        $count = $trendingService->recalculateScores();
        $this->info("Successfully updated trending scores for {$count} articles.");

        return Command::SUCCESS;
    }
}
