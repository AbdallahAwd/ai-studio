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
                    'text' => $data['plain_text'],
                    'language' => $data['language_code'],
                    'speaker_wav' => $customVoiceUrl,
                ];
                $response = Http::post('https://replicate.com/api/models/sigil-wen/xtts/versions/408deaff0c9ba77846ce43a9b797fa9d08ce1a70830ad74c0774c55fd3aabce5/predictions', ['inputs' => $input]);

                return response()->json([
                    "id" => "{$response->json()['uuid']}",
                ]);

            } else {
                return response()->json(['message' => 'Invalid audio file'], 400);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => "Error {$th->getMessage()}"], 401);

        }

    }
    public function getGeneratedDub(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
            ]);

            $response = Http::get("https://replicate.com/api/models/sigil-wen/xtts/versions/408deaff0c9ba77846ce43a9b797fa9d08ce1a70830ad74c0774c55fd3aabce5/predictions/{$request['id']}", );
            $data = $response->json()['prediction'];
            $output = $data['output'];
            if ($output != null) {

                return response()->json([
                    "status" => 'done',
                    "output" => $output,

                ]);

            }
            return response()->json([
                "status" => $data['status'],
                "log" => $data['run_logs'],
            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => "Error {$th->getMessage()}"], 401);

        }
    }
}
