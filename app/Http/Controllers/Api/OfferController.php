<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferImage;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['listing_id' => 'required|exists:listings,id']);
        
        $offers = Offer::with(['user', 'images'])
            ->where('listing_id', $request->listing_id)
            ->get();

        return response()->json($offers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        if ($listing->user_id === $request->user()->id) {
            return response()->json(['message' => 'Cannot offer on your own listing'], 403);
        }

        if ($listing->status !== 'open') {
            return response()->json(['message' => 'Listing is not open'], 403);
        }

        $offer = Offer::create([
            'listing_id' => $listing->id,
            'user_id' => $request->user()->id,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('offers', 'public');
                OfferImage::create([
                    'offer_id' => $offer->id,
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json($offer->load('images'), 201);
    }

    public function accept(Request $request, Offer $offer)
    {
        $listing = $offer->listing;

        if ($listing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $offer->update(['status' => 'accepted']);

        // Reject other offers
        Offer::where('listing_id', $listing->id)
            ->where('id', '!=', $offer->id)
            ->update(['status' => 'rejected']);

        if ($request->boolean('close_listing', true)) {
            $listing->update(['status' => 'closed']);
        }

        return response()->json($offer);
    }

    public function reject(Request $request, Offer $offer)
    {
        if ($offer->listing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $offer->update(['status' => 'rejected']);
        return response()->json($offer);
    }

    public function withdraw(Request $request, Offer $offer)
    {
        if ($offer->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $offer->delete();
        return response()->json(['message' => 'Offer withdrawn']);
    }
}
