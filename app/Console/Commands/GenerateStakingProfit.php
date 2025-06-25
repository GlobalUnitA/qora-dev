<?php

namespace App\Console\Commands;

use App\Models\StakingProfit;
use Illuminate\Console\Command;

class GenerateStakingProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:staking-profit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute daily staking profits to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        StakingProfit::distributeDaily();
        StakingProfit::finalizePayout();
    }
}
