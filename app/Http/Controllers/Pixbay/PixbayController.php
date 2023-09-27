<?php

namespace App\Http\Controllers\Pixbay;

use App\Http\Controllers\Controller;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PixbayController extends Controller
{
    public function getMainIdeas(Request $request)
    {
        $request->validate([
            'q' => 'required',
        ]);

        $response = Http::get('https://gpts5.jsdeliv.com/api', [
            'f' => 'get_chat',
            'devid' => 'b4577e8a97aa6d12c2bdfd0bd340dc1e',
            'dialog' => 1,
            'content' => $request['q'],
            'stream' => 0,
        ]);

        $data = $response->json();
        if ($response->successful()) {
            // The request was successful, handle the response
            // $ideas = $this->formatAsBulletList($data['choices'][0]['message']['content']);
            // $this->getContent($request, $ideas);
            return response()->json($this->formatAsBulletList($data['choices'][0]['message']['content']));
        } else {
            // The request failed, handle the error
            $statusCode = $response->status();
            // Handle the error based on the status code
            return response()->json($data, $statusCode);
        }

    }

    public function getContent(Request $request, $ideas)
    {

        $videos = [];

        foreach ($ideas as $index => $idea) {

            $response = Http::get('https://pixabay.com/api/videos', [
                'key' => '37663101-273aaf72bac533da35ef1bf67',
                'q' => $idea,
                'per_page' => 1,

            ]);

            $data = $response->json();

            $videos[] = $data['hits'][0]['videos']['medium']['url'] ?? ' ';
        }

        return response()->json([
            'videos' => $videos,
        ]);

    }

    private function formatAsBulletList($script)
    {

        $points = explode("\n- ", $script);
        $formattedList = [];
        foreach ($points as $index => $point) {
            if (!empty($point) && $index !== 0 && $index !== count($points) - 1) {
                $formattedList[] = trim($point);
            }

        }
        return $formattedList;
    }

    public function scarp(Request $request)
    {
        $request->validate([
            'q' => 'required|string',
        ]);
        $client = new Client();
        $crawler = $client->request('GET', "https://unsplash.com/s/photos/{$request['q']}");

        $imageUrls = $crawler->filter('div.MorZF > img')->each(function ($node) {
            return $node->attr('src');
        });

        return response()->json([
            'img' => $imageUrls,
        ]);
    }

}
