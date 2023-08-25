<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLettersCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $lettersCount = $request['letters_count'];

            if ($lettersCount !== null) {
                $user = Auth::user();
                $user->letters_count -= $lettersCount;
                if ($user->letters_count > 0) {

                    $user->save();
                } else {
                    return response()->json(['message' => 'There is not enough letters']);
                }
                // Update the authenticated user's object
                Auth::user()->letters_count = $user->letters_count;

            }
        }

        return $response;
    }
}
