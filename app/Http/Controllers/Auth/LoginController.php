<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    // register

    public function register(Request $request)
    {
        $exists = User::where('email', $request['email'])->first();

        if ($exists) {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = [
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ];

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user(); // Get the authenticated user
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'user' => $user, 'token' => $token], 200);

        }

        $validatedData = $request->validate([
            'name' => 'between:5,20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user); // Authenticate the newly registered user

        $token = $user->createToken('authToken')->plainTextToken;
        $user->letters_count = 1000;
        $platform = $this->detectPlatform($request);

        if ($platform === 'android' || $platform === 'ios') {

            return response()->json(['message' => 'Registered Successfully', 'user' => $user, 'token' => $token], 201);
        }
        // return response()->json(['message' => 'Registered Successfully', 'user' => $user, 'token' => 'provided if the request done from phone'], 201);
        // TODO remove this
        return response()->json(['message' => 'Registered Successfully', 'user' => $user, 'token' => $token], 201);

    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
            Auth::guard('web')->logout();
            Session::flush();
        }

        return response()->json(['message' => 'Logged out successfully']);

    }

    protected function detectPlatform(Request $request)
    {
        // You can inspect request headers or user agent to detect the platform
        $userAgent = $request->header('User-Agent');

        if (strpos($userAgent, 'Android') !== false) {
            return 'android';
        } elseif (strpos($userAgent, 'iOS') !== false) {
            return 'ios';
        } else {
            return 'pc';
        }
    }
}
