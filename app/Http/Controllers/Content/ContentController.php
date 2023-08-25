<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\UserVoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function scriptGeneratorOptions()
    {
        $response = Http::withHeaders([
            'Authorization' => 'BEARER eyJhbGciOiJFUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxOTY1ODQsInRva2VuX3R5cGUiOiJhY2Nlc3NfdG9rZW4iLCJkZXZpY2VfaWQiOiJkZjg2NTNjZGE4Mzk4YjQ1IiwicGxhdGZvcm0iOiJhbmRyb2lkIiwiZXhwIjoxNjk1ODAxNTkxfQ.8rwJWCwJ-oUXJbD1K3w3t6_yBYeoy4dYO-uPflBdNgOXP8JqVQMlmPFRjnd5yqccSf3ZzAKVJHZDMlhv6pzfGw',

        ])->get('https://api-raven.clipp.ai/v1/ai_scripts_prompt_options');

        if ($response->successful()) {
            $data = $response->json(); // Convert the response to JSON

            return response()->json($data);
        } else {
            // Handle the error
            $statusCode = $response->status();
            $errorData = $response->json();
        }

    }

    public function scriptGenerator(Request $request)
    {
        $data = $request->validate([
            'script_tone' => 'required',
            'script_form' => 'required',
            'description' => 'required|max:1000',
        ]);
        $response = Http::withHeaders([
            'Authorization' => 'BEARER ' . env('SCRIPT_API_KEY'),

        ])->withBody(json_encode($data), 'application/json')
            ->post('https://api-raven.clipp.ai/v1/ai_scripts');

        if ($response->successful()) {
            $data = $response->json(); // Convert the response to JSON

            return response()->json($data);
        } else {
            // Handle the error
            $statusCode = $response->status();
            $errorData = $response->json();

            return response()->json($errorData, $statusCode);

        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function Generated(Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
        ]);
        $id = $data['id'];
        $response = Http::withHeaders([
            'Authorization' => 'BEARER ' . env('SCRIPT_API_KEY'),

        ])->get("https://api-raven.clipp.ai/v1/ai_scripts/$id");

        if ($response->successful()) {
            $data = $response->json(); // Convert the response to JSON

            return response()->json($data);
        } else {
            // Handle the error
            $statusCode = $response->status();
            $errorData = $response->json();

            return response()->json($errorData, $statusCode);

        }

    }

    /**
     * Display the specified resource.
     */
    public function voices()
    {
        $response = Http::get('https://api.elevenlabs.io/v1/voices');

        if ($response->successful()) {

            return response()->json($response->json());
        } else {

            return response()->json($response->json(), $response->status());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function generateVoice(Request $request)
    {
        $data = $request->validate([
            'model_id' => 'required',
            'voice_id' => 'required',
            'text' => 'required|min:20',
        ]);

        $response = Http::withHeaders([
            'xi-api-key' => env('ELEVEN_LABS_API_KEY'),
        ])->withBody(json_encode($data), 'application/json')
            ->post("https://api.elevenlabs.io/v1/text-to-speech/{$data['voice_id']}?optimize_streaming_latency=0");

        if ($response->successful()) {
            $audioData = $response->body();
            $fileName = 'AI-Studio-' . Str::random() . '.mp3'; // Provide a suitable file name
            Storage::put('public/audios/' . $fileName, $audioData); // Store the audio file

            $audioUrl = Storage::url('public/audios/' . $fileName); // Generate the URL for the stored file

            // Dispatch a job to delete the audio file after a certain time interval (e.g., 24 hours)
            // (new DeleteAudioFile($fileName))->delay(Carbon::now()->addSeconds(90));

        } else {
            return response()->json(['message' => $response->json()], 500);
        }

        return response()->json(['audio_url' => $audioUrl], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // TODO Still Not finished
    /* TODO :
    1- Make file got from user phone
    2- assign it to the request
     */
    public function cloneVoice(Request $request)
    {
        // $filePath1 = public_path('storage/audios/AI-Studio-L4LUlekGwPCC8Kqd.mp3');
        $data = $request->validate([
            'audio_file' => 'required|mimes:mp3,wav,aac,m4a',
            'name' => 'required|string|max:20',

            'lang' => 'required|string',
        ]);
        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $file = $request->file('audio_file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('audios', $fileName, 'public'); // Store in 'public/audio' directory
            $customVoiceUrl = asset('storage/audios/' . $fileName);
            $filePath = Storage::disk('public')->path('audios/' . $fileName);

            // return response()->json(['url' => $customVoiceUrl], 201);

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'xi-api-key' => env('ELEVEN_LABS_API_KEY'),
            ])
                ->attach('files', file_get_contents($filePath), 'sample.mp3', ['Content-Type' => 'audio/mpeg'])
                ->post('https://api.elevenlabs.io/v1/voices/add', [
                    'name' => $request['name'],

                    'labels' => json_encode(['language' => $request['lang']]),
                ]);
            $dir = "public/audios/{$fileName}";
            if ($response->successful()) {
                $user = Auth::user();
                UserVoices::create([
                    'user_id' => $user->id,
                    'name' => $request['name'] ?? 'AI Studio',
                    'lang' => $request['lang'] ?? 'English',
                    'voice_id' => $response->json()['voice_id'],
                ]);
                Storage::delete($dir);
                return response()->json($response->json());
            } else {
                $responseData = $response->json()['detail']['message'];
                Storage::delete($dir);

                return response()->json(['message' => $responseData], $response->status());

            }
        }

    }

    public function getUserVoices()
    {
        $userId = Auth::user()->id;
        $userVoices = UserVoices::where('user_id', $userId)->get();

        return response()->json(['voices' => $userVoices]);
    }

}
