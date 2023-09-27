<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteAudioFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fileName;
    /**
     * Create a new job instance.
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $directory = 'public/audios/';
        Storage::delete($directory . $this->fileName);

    }
}
