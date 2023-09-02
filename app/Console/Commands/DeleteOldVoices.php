<?php

namespace App\Console\Commands;

use App\Models\UserVoices;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class DeleteOldVoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voices:delete-old';
    protected $description = 'Delete voices that have not been updated in the last 30 days';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Query and retrieve records older than 30 days
        $voices = UserVoices::where('updated_at', '<', $thirtyDaysAgo)->get();

        if ($voices->isEmpty()) {
            $this->info('No voices found to delete.');
            return;
        }

        foreach ($voices as $voice) {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'xi-api-key' => env('ELEVEN_LABS_API_KEY'), // Replace with your actual xi-api-key
            ])->delete("https://api.elevenlabs.io/v1/voices/{$voice->voice_id}");

            if ($response->successful()) {
                // Voice was deleted successfully, delete the local record
                $voice->delete();
                $this->info("Voice with ID {$voice->voice_id} deleted successfully.");
            } else {
                $this->error("Failed to delete voice with ID {$voice->voice_id}: {$response->json()['detail']}");
            }
        }
    }
}
