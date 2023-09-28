<?php

namespace App\Http\Controllers\Replicate;

use App\Http\Controllers\Controller;
use getID3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DubController extends Controller
{
    // 1- Take Audio Form User
    // 2- Take Send it to replicate whisper to get plain Text
    // 3-Take plain text and send it to

    public function generateDub(Request $request)
    {
        try {
            $data = $request->validate([
                "plain_text" => "required|between:20,1000",
                "language_code" => 'required|between:2,5',
                "wav_file" => 'required|file|mimes:wav',
            ]);
            if ($request->hasFile('wav_file') && $request->file('wav_file')->isValid()) {
                $file = $request->file('wav_file');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('audios', $fileName, 'public'); // Store in 'public/audio' directory
                $customVoiceUrl = asset('storage/audios/' . $fileName); // Get full URL to the uploaded file
                $audioUrl = Storage::url('public/audios/' . $fileName);

                // Check audio duration
                $getID3 = new getID3();
                $audioFileInfo = $getID3->analyze($file->getRealPath());
                $audioDuration = $audioFileInfo['playtime_seconds'];

                if ($audioDuration > 600) {
                    // Delete the uploaded file

                    Storage::disk('public')->delete('audios/' . $fileName);

                    return response()->json(['message' => 'Audio is too long (max 10 min)'], 400);
                }
                $input = [
                    'version' => '408deaff0c9ba77846ce43a9b797fa9d08ce1a70830ad74c0774c55fd3aabce5',
                    'input' => [
                        'text' => $data['plain_text'],
                        'language' => $data['language_code'],
                        // 'speaker_wav' => $customVoiceUrl,
                        'speaker_wav' => 'https://pbxt.replicate.delivery/JYDf6xQfT7cOYljjNXbXxgauFQ1ZXJZf5GLNsth7FhsMU7IO/yosun-voice-acting.wav',
                    ],
                ];
                $response = Http::withHeader(
                    'Authorization', 'Token ' . env('REPLICATE_TOKEN'),
                )->timeout(600)->post('https://api.replicate.com/v1/predictions', $input);

                // return response()->json($response->json());
                return response()->json([
                    "id" => $response->json()['id'],
                    "input" => $response->json()['input'],
                ]);

            } else {
                return response()->json(['message' => 'Invalid audio file'], 400);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => "Error {$th->getMessage()}"], 400);

        }

    }
    public function getGeneratedDub(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
            ]);

            $response = Http::withHeader(
                'Authorization', 'Token ' . env('REPLICATE_TOKEN'),
            )->get("https://api.replicate.com/v1/predictions/{$request['id']}", );
            $data = $response->json();
            if (isset($data['detail'])) {
                return response()->json($data, 400);
            }
            $status = $data['status'];

            if ($status === 'succeeded') {
                $output = $data['output'];
                $getID3 = new getID3();
                $mp3Contents = file_get_contents($output);
                if ($mp3Contents === false) {
                    return response()->json([
                        "status" => "Error: Unable to fetch the remote Audio file.",
                    ]);
                }
                $localFilePath = storage_path('app/public/audios/' . time() . '.mp3');
                file_put_contents($localFilePath, $mp3Contents);

                $audioFileInfo = $getID3->analyze($localFilePath);

                $audioDuration = (float) $audioFileInfo['playtime_seconds'];

                return response()->json([
                    "status" => 'done',
                    "duration" => $audioDuration,
                    "output" => $output,

                ]);

            }
            return response()->json([
                "status" => $data['status'],
                "log" => $data['logs'],
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => "Error {$th->getMessage()}"], 400);

        }
    }

    public function addMusic(Request $request)
    {
        try {
            $data = $request->validate([
                "audio" => "required|file",
            ]);
            if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
                $file = $request->file('audio');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('audios', $fileName, 'public'); // Store in 'public/audio' directory
                $customVoiceUrl = asset('storage/audios/' . $fileName); // Get full URL to the uploaded file
                $audioUrl = Storage::url('public/audios/' . $fileName);

                $input = [
                    "version" => "cd128044253523c86abfd743dea680c88559ad975ccd72378c8433f067ab5d0a",
                    "input" => [
                        "audio" => $customVoiceUrl,
                    ],
                ];
                // "audio" : $customVoiceUrl,

                // return response()->json($input);
                $response = Http::withHeader(
                    'Authorization', 'Token ' . env('REPLICATE_TOKEN'),
                )->timeout(600)->post('https://api.replicate.com/v1/predictions', $input);

                return response()->json([
                    'id' => $response->json()['id'],
                    'input' => $response->json()['input'],
                ], $response->status());

            } else {
                return response()->json(['message' => 'Invalid audio file'], 400);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => "Error {$th->getMessage()}"], 400);

        }

    }
    public function getMusic(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
            ]);

            $response = Http::withHeader(
                'Authorization', 'Token ' . env('REPLICATE_TOKEN'),
            )->get("https://api.replicate.com/v1/predictions/{$request['id']}", );
            $data = $response->json();
            $status = $data['status'];
            // return response()->json($data);
            if ($status === 'succeeded') {
                $output = $data['output'];

                return response()->json([
                    "status" => 'done',

                    "output" => $output,

                ]);

            }
            return response()->json([
                "status" => $data['status'],
                "log" => $data['logs'],
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => "Error {$th->getMessage()}"], 400);

        }

    }
}
