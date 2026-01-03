<?php

namespace App\Console\Commands;

use App\Services\ShortcodePoolManager;
use Illuminate\Console\Command;

class MaintainShortcodePool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shortcode:maintain-pool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and maintain shortcode pool size';

    /**
     * Execute the console command.
     */
    public function handle(ShortcodePoolManager $poolManager)
    {
        $stats = $poolManager->getStats();

        $this->info('Pool Status:');
        $this->info("  Current: {$stats['current_size']}");
        $this->info("  Target:  {$stats['target_size']}");
        $this->info("  Min:     {$stats['min_size']}");
        $this->info("  Status:  {$stats['status']}");

        if ($stats['needs_refill']) {
            $this->info('Refilling pool...');

            $needed = $stats['target_size'] - $stats['current_size'];
            $inserted = $poolManager->refill($needed);

            $this->info("Refilled {$inserted} shortcodes.");
        } else {
            $this->info('Pool is healthy.');
        }

        return Command::SUCCESS;
    }
}
