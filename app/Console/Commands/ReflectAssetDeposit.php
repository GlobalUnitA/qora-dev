<?php

namespace App\Console\Commands;

use App\Models\AssetTransfer;
use App\Models\IncomeTransfer;
use Illuminate\Console\Command;

class ReflectAssetDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reflect:asset-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reflect deposit transactions to user assets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AssetTransfer::reflectDeposit();
        IncomeTransfer::reflectDeposit();
    }
}
