<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Listing;
use App\Models\DeliveryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'reviewed_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        $user = Auth::user();

        // Check if delivery completed
        $delivery = DeliveryRequest::where('listing_id', $listing->id)
            ->where('status', 'delivered')
            ->first();

        if (!$delivery) {
            return response()->json(['message' => 'Cannot review before delivery is completed'], 422);
        }

        // Cannot review same person for same listing twice
        $exists = Review::where('listing_id', $listing->id)
            ->where('reviewer_id', $user->id)
            ->where('reviewed_id', $request->reviewed_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'You have already reviewed this user for this listing'], 422);
        }

        $review = Review::create([
            'listing_id' => $request->listing_id,
            'reviewer_id' => $user->id,
            'reviewed_id' => $request->reviewed_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review, 201);
    }

    public function index($user_id)
    {
        $reviews = Review::where('reviewed_id', $user_id)
            ->with(['reviewer', 'listing'])
            ->latest()
            ->paginate(20);

        return response()->json($reviews);
    }
}