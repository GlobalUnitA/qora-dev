<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GrantRankBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grant:rank-bonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant rank bonus to user incomes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::channel('bonus')->info('Starting rank bonus grant process');

        User::with('profile')->chunk(100, function ($users) {
            foreach ($users as $user) {
               $user->profile->rankBonus();
            }
        });

        Log::channel('bonus')->info('Finished rank bonus grant process');
    }
}