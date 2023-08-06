<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneTempUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prune:temp-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune the temporary folder that holds uploaded image';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = storage_path('temp/uploads');
        $files = Storage::files($path);

        // delete files that are old for at least 7 days

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::lastModified($file));
            if ($lastModified->lt(now()->subDays(7))) {
                Storage::delete($file);
                // perform other actions
            }
        }
    }
}
