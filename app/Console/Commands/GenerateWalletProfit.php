<?php

namespace App\Console\Commands;

use App\Models\WalletProfit;
use Illuminate\Console\Command;

class GenerateWalletProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:wallet-profit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute daily wallet profits to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WalletProfit::distributeDaily();
    }
}
