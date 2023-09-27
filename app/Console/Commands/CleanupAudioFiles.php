<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupAudioFiles extends Command
{
    protected $signature = 'audio:cleanup';
    protected $description = 'Delete old audio files';

    public function handle()
    {
        $directory = 'public/audios/';
        $files = Storage::files($directory);
        if (!$files) {
            $this->info('No files to delete');
        }
        foreach ($files as $file) {
            // $fileDate = Storage::lastModified($file);
            // $timeDifference = Carbon::now()->diffInHours(Carbon::createFromTimestamp($fileDate));

            // if ($timeDifference >= 24) {
            Storage::delete($file);
            $this->info('Deleted audio file: ' . $file);
            // }
        }
    }
}
