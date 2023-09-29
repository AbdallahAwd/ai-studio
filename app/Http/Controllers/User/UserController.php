<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'version' => '0.0.1',
            // 'what\'s new?' => ',
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function updateLetter(Request $request)
    {
        //
        $request->validate([
            'count' => 'required|max:100',
        ]);
        $user = Auth::user();
        $user->letters_count += $request['count'];
        $user->save();
        return response()->json([
            'success' => true,
            'letters_count' => $user->letters_count,
        ]);
    }

    public function watchAd()
    {
        $user = Auth::user();

        // Check if the user already has a token
        $existingToken = $user->tokens->where('name', 'ad-watched-token')->where('expires_at', '>=', now())->first();
        if (!$existingToken) {
            $user->letters_count += 500;
            $user->save();

            $expiration = now()->addMinutes(1); // Set the expiration time (1 hour in this example)
            $token = $user->createToken('ad-watched-token', ['*']);
            $token->accessToken->update([
                'expires_at' => $expiration,
            ]);

            return response()->json([
                'success' => true,
                'letters_count' => $user->letters_count,
                'api_token' => $token->plainTextToken,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'An expiring token is already generated for this user.',
            'token' => $existingToken,

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
