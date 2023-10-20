<?php

namespace App\Http\Controllers\Replicate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReplicateController extends Controller
{

// ...

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

        } else {
            return response()->json(['message' => 'Invalid audio file'], 400);
        }

        // $version = '7f686e243a96c7f6f0f481bcef24d688a1369ed3983cea348d1f43b879615766';
        // $data = [
        //     'audio_path' => $customVoiceUrl,
        //     // 'audio_path' => 'https://firebasestorage.googleapis.com/v0/b/super-ai-5fee1.appspot.com/o/ElevenLabs_2023-08-12T01_47_24.000Z_Callum.mp3?alt=media&token=d81a4866-a6a2-47d3-bb6d-e8a6680e0d0c',
        //     'format' => 'srt', // Use the full URL to the uploaded audio file
        //     'model_name' => 'base',
        // ];
        $data = [
            // "audio" => "https://replicate.delivery/pbxt/JjUYezOdQytAXVrWo7O2yvTsFp4QVHOPqe8VjOZXmIgyQZl8/mo.mp4",
            "audio" => $customVoiceUrl,
            "model" => "large",
            "translate" => false,
            "temperature" => 0,
            "transcription" => "srt",
            "suppress_tokens" => "-1",
            "logprob_threshold" => -1,
            "no_speech_threshold" => 0.6,
            "condition_on_previous_text" => true,
            "compression_ratio_threshold" => 2.4,
            "temperature_increment_on_fallback" => 0.2,
        ];
        $version = '91ee9c0c3df30478510ff8c8a3a545add1ad0259ad3a9f78fba57fbc05ee64f7';
        $response = Http::withHeader(
            'Authorization', 'Token ' . env('REPLICATE_TOKEN'),
        )->timeout(600)->post('https://api.replicate.com/v1/predictions', [
            'version' => $version,
            'input' => $data,
        ]);

        return response()->json([
            'id' => $response->json()['id'],
            'input' => $response->json()['input'],

        ]);
    }

    public function generateSubtitleWithURL(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);
        $extension = pathinfo(parse_url($request['url'], PHP_URL_PATH), PATHINFO_EXTENSION);

        $audioExtensions = ['mp3', 'm4a', 'wav', 'flac']; // Add more audio extensions as needed

        if (in_array(strtolower($extension), $audioExtensions)) {
            // The URL points to an audio file
            // You can add your logic here
            $data = [
                "audio" => $request['url'],
                "model" => "large",
                "translate" => false,
                "temperature" => 0,
                "transcription" => "srt",
                "suppress_tokens" => "-1",
                "logprob_threshold" => -1,
                "no_speech_threshold" => 0.6,
                "condition_on_previous_text" => true,
                "compression_ratio_threshold" => 2.4,
                "temperature_increment_on_fallback" => 0.2,
            ];
            $version = '91ee9c0c3df30478510ff8c8a3a545add1ad0259ad3a9f78fba57fbc05ee64f7';

            $response = Http::withHeader(
                'Authorization', 'Token ' . env('REPLICATE_TOKEN'),
            )->timeout(600)->post('https://api.replicate.com/v1/predictions', [
                'version' => $version,
                'input' => $data,
            ]);

            return response()->json([
                'id' => $response->json()['id'],
                'input' => $response->json()['input'],

            ]);

        } else {
            return response()->json([
                'message' => 'This is not an audio url please recheck it',
            ]);

        }

    }

    public function generatedSubtitle(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $response = Http::withHeader(
            'Authorization', 'Token ' . env('REPLICATE_TOKEN'),
        )->get("https://api.replicate.com/v1/predictions/{$request['id']}");
        $data = $response->json();
        /**
        temp return
         */
        $status = $data['status'];
        if ($status === 'succeeded') {
            $output = $data['output']['transcription'];
            $formattedSubtitles = $this->generateIntervals($output);
            // TODO
            // $srtContent = $this->generateTextFormat($formattedSubtitles, false);

            return response()->json([
                "status" => 'done',
                "output" => $output,
                "fotmated_subtitle" => $formattedSubtitles,
            ]);

        }
        // return response()->json($data);
        return response()->json([
            "status" => $data['status'],
            "logs" => $data['logs'],
        ]);

    }

    public function generateIntervals($output)
    {
        $input = trim($output);
        $input = str_replace("\n\n", "\n", $output);

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
