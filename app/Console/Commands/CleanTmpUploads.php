<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CleanTmpUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploads:clean-tmp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary images from uploads/tmp older than 1 hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = Storage::disk('public')->allFiles('uploads/tmp');
        $now = now()->timestamp;
    
        foreach ($files as $file) {
            $filename = basename($file);
    
            if (preg_match('/^(\d+)_/', $filename, $matches)) {
                $uploadedAt = (int) $matches[1];
    
                if ($uploadedAt < now()->subHour()->timestamp) {
                    Storage::disk('public')->delete($file);
                    $this->info("Deleted: {$file}");
                } else {
                    $this->info("Kept (not expired): {$file}");
                }
            } else {
                $this->warn("Skipped (invalid format): {$file}");
            }
        }
    
        $this->info('Temporary image cleanup completed!');
    }
}
