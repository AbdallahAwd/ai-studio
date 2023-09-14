<?php

namespace App\Http\Controllers\Replicate;

use App\Http\Controllers\Controller;
use getID3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ReplicateController extends Controller
{

// ...

    public function cloneVoice(Request $request)
    {
        // Validate the request data
        $request->validate([
            'audio_file' => 'required|mimes:mp3,wav',
            'text' => 'required|string',
        ]);

        // Upload the audio file
        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $file = $request->file('audio_file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('audios', $fileName, 'public'); // Store in 'public/audio' directory
            $customVoiceUrl = asset('storage/audios/' . $fileName); // Get full URL to the uploaded file

            // Check audio duration
            $getID3 = new getID3();
            $audioFileInfo = $getID3->analyze($file->getRealPath());
            $audioDuration = $audioFileInfo['playtime_seconds'];

            if ($audioDuration > 40) {
                // Delete the uploaded file

                Storage::disk('public')->delete('audios/' . $fileName);

                return response()->json(['message' => 'Audio is too long (max 40 seconds)'], 400);
            }
        } else {
            return response()->json(['message' => 'Invalid audio file'], 400);
        }

        // Prepare the data for the API request
        $data = [
            'text' => $request['text'],
            'voice_a' => 'custom_voice',
            'custom_voice' => 'https://firebasestorage.googleapis.com/v0/b/super-ai-5fee1.appspot.com/o/ElevenLabs_2023-08-11T18_51_24.000Z_Adam_0hitQkQoT5ki9GZYHmda.mp3?alt=media&token=5e1b4ce0-e8ae-49f4-b89a-f967a8e8c021', // Use the full URL to the uploaded audio file
            'preset' => 'fast',
        ];

        // Send the API request
        $response = Http::
            post('https://replicate.com/api/models/afiaka87/tortoise-tts/versions/e9658de4b325863c4fcdc12d94bb7c9b54cbfe351b7ca1b36860008172b91c71/predictions', ['inputs' => $data]);

        // Process and return the response
        if ($response->successful()) {

            return response()->json($response->json());
        } else {
            return response()->json(['message' => 'API request failed'], $response->status());
        }
    }

    public function generateSubtitle(Request $request)
    {
        $request->validate([
            'audio_file' => 'required',
        ]);
        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $file = $request->file('audio_file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('audios', $fileName, 'public'); // Store in 'public/audio' directory
            $customVoiceUrl = asset('storage/audios/' . $fileName);
            $audioFilePath = storage_path('app/public/audios/' . $fileName);
            // Check audio duration
            // $getID3 = new getID3();
            // $audioFileInfo = $getID3->analyze($audioFilePath);
            // $audioDuration = $audioFileInfo['playtime_seconds'];
            // $user = Auth::user();
            // if ($user->letters_count <= 10000) {

            // if ($audioDuration > 300) {
            // Delete the uploaded file

            // Storage::disk('public')->delete('audios/' . $fileName);

            // return response()->json(['message' => 'Audio is too long (max 5 min for users less than 10K letters)'], 400);
            // }
            // }

        } else {
            return response()->json(['message' => 'Invalid audio file'], 400);
        }
        /**

        "audio_path": "https://replicate.delivery/pbxt/J60yrm1ftAZeiZGbzaUjlH9fbenp8utC0hhdweKj75lXXDsA/andrew.mp3",
        "format": "srt",
        "model_name": "tiny"
         */
        $data = [
            'audio_path' => $customVoiceUrl,
            // 'audio_path' => 'https://firebasestorage.googleapis.com/v0/b/super-ai-5fee1.appspot.com/o/ElevenLabs_2023-08-12T01_47_24.000Z_Callum.mp3?alt=media&token=d81a4866-a6a2-47d3-bb6d-e8a6680e0d0c',
            'format' => 'srt', // Use the full URL to the uploaded audio file
            'model_name' => 'tiny',
        ];

        $response = Http::post('https://replicate.com/api/models/m1guelpf/whisper-subtitles/versions/7f686e243a96c7f6f0f481bcef24d688a1369ed3983cea348d1f43b879615766/predictions', ['inputs' => $data]);

        return response()->json($response->json());
    }

    public function generatedSubtitle(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $response = Http::get("https://replicate.com/api/models/m1guelpf/whisper-subtitles/versions/7f686e243a96c7f6f0f481bcef24d688a1369ed3983cea348d1f43b879615766/predictions/{$request['id']}");
        $data = $response->json()['prediction'];
        $output = $data['output'];
        if ($output != null) {
            $formattedSubtitles = $this->generateIntervals($output);
            // TODO
            $srtContent = $this->generateTextFormat($formattedSubtitles, false);

            return response()->json([
                "status" => 'done',
                "output" => $srtContent,
                "fotmated_subtitle" => $formattedSubtitles,
            ]);

        }
        return response()->json([
            "status" => $data['status'],
            "log" => $data['run_logs'],
        ]);

    }

    public function generateIntervals($output)
    {
        $input = trim($output['subtitles'] ?? $output);

        $segments = explode("\n", $input);

        $formattedSubtitles = [];

        for ($i = 0; $i < count($segments) / 3; $i++) {
            $intervals = explode(" --> ", $segments[3 * $i + 1] ?? "");
            $text = trim($segments[3 * $i + 2] ?? "");

            if (!empty($intervals) && $text !== "") {

                $formattedSubtitles[] = [
                    "display_text" => $text,
                    "interval" => $intervals,
                ];

            }
        }
        return $formattedSubtitles;

    }

    private function intervalsToSeconds($intervals)
    {
        $secondsList = [];

        foreach ($intervals as $interval) {
            list($hms, $ms) = explode(".", $interval);
            list($h, $m, $s) = explode(":", $hms);

            $seconds = ($h * 3600) + ($m * 60) + $s + ($ms / 1000);

            $secondsList[] = $seconds;
        }

        return $secondsList;

    }

    public function generateSrtFormat(Request $request)
    {
        $data = $request['data'];
        $srtContent = $this->generateTextFormat($data, false);

        return response()->json([
            'srt' => $srtContent,
        ]);
    }

    public function translateSrt(Request $request)
    {
        $request->validate([
            'data' => 'required',

            "target" => 'required',

        ]);
        $srtContent = $this->generateTextFormat($request['data'], true);
        $encodedSrtContent = urlencode($srtContent);

        try {
            //code...
            $url = "https://translate-pa.googleapis.com/v1/translate?query.text={$encodedSrtContent}&query.source_language=auto&query.target_language={$request['target']}&query.display_language=en-US&params.client=at&data_types=16&data_types=1&data_types=10&data_types=21&data_types=6&data_types=7&data_types=5&data_types=17&data_types=12&data_types=8&data_types=26&params.request_token=648701.1917573876";
            $response = Http::withHeaders([
                'x-goog-spatula' => 'CkEKIWNvbS5nb29nbGUuYW5kcm9pZC5hcHBzLnRyYW5zbGF0ZRocSkxza3dGNUg0Szc2YUtXS2RtRjUyYllUcGdBPRIg6pM8edVKsCm8ndm980/hj/IsuvAOKuFQuSf20W8SWIIYzdS8/tLJibc0IM3Qr9fpjIOAsgEqSQBeaoCcSqXDraanFbhDeFHWPF4xjUNXEbGBycw+YXNGmZSIXOJGDCAt603UNIAlV4IZBRfsgSdWbzjXZKLHC9yC4YSpRpQL4fk=',
                'x-server-token' => 'CAMSDg0Bov-NDYYGFQPjoesR',
            ])->get($url);
            $data = $response->json();
            if ($response->successful()) {
                $d = $this->generateIntervals($data['translation']);
                return response()->json([
                    'output' => $data['translation'],
                    'fotmated_subtitle' => $d,
                ]);
            }
            return response()->json([
                'url' => $url,
                'error' => $response->body(),
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'url' => $url,
                'error' => $th->getTrace(),
            ]);
        }

    }

    private function generateTextFormat($data, $isTranslate)
    {
        $srtContent = '';

        foreach ($data as $index => $item) {
            $index = $index + 1;
            $startTime = $item['interval'][0];
            $endTime = $item['interval'][1];
            $displayText = $item['display_text'];
            if (!$isTranslate) {

                $srtContent .= "{$index}\n{$startTime} --> {$endTime}\n{$displayText}\n\n";
            } else {

                $srtContent .= "{$index}\n{$startTime} --> {$endTime}\n{$displayText}\n";
            }
        }

        return $srtContent;
    }

    public function supportedLanguages(Request $request)
    {
        $response = Http::withHeaders([
            'x-goog-spatula' => env('GOOGLE_TRANSLATE_KEY'), // Replace with your actual config key name
        ])->get('https://translate-pa.googleapis.com/v1/supportedLanguages', [
            'display_language' => $request['lang_code'] ?? 'en',
            'client' => 'at',
        ]);

// Check if the request was successful (status code 200)
        if ($response->successful()) {
            $data = $response->json(); // Parse JSON response
            // Handle the response data as needed
            return $data;
        } else {
            // Handle the error if the request was not successful
            return response()->json(['error' => 'API request failed'], $response->status());
        }

    }

}
