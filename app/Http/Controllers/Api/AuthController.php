<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function socialLogin(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:google,apple',
            'token' => 'required',
            'phone' => 'nullable|string',
        ]);

        $provider = $request->provider;
        $token = $request->token;

        try {
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($token);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::updateOrCreate(
            ['provider' => $provider, 'provider_id' => $socialUser->getId()],
            [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar_url' => $socialUser->getAvatar(),
                'phone' => $request->phone,
                'is_active' => true,
            ]
        );

        $sanctumToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $sanctumToken,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
