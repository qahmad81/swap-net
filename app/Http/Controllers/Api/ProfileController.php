<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $user->loadCount(['networkMembers as networks_count', 'listings']);
        
        // Simple average rating calculation
        $avgRating = $user->reviews()->avg('rating') ?? 0;
        
        return response()->json([
            'user' => $user,
            'networks_count' => $user->networks_count,
            'listings_count' => $user->listings_count,
            'avg_rating' => round($avgRating, 1),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'sometimes|string', // Assuming base64 or URL
            'locale' => 'sometimes|string|max:10',
        ]);

        $user = $request->user();
        
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('avatar')) $user->avatar_url = $request->avatar;
        if ($request->has('locale')) $user->locale = $request->locale;
        
        $user->save();

        return response()->json($user);
    }
}
