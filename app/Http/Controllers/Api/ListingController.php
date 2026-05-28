<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'network_id' => 'required|exists:networks,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $query = Listing::with(['user', 'category', 'images'])
            ->withCount('offers')
            ->where('network_id', $request->network_id)
            ->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->paginate(15));
    }

    public function show(Listing $listing)
    {
        $listing->load(['user', 'category', 'images']);
        $listing->offers_count = $listing->offers()->count();
        return response()->json($listing);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'wanted_item' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'network_id' => 'required|exists:networks,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $listing = Listing::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'network_id' => $request->network_id,
            'title' => $request->title,
            'description' => $request->description,
            'wanted_item' => $request->wanted_item,
            'type' => 'offer', // Defaulting to offer
            'status' => 'open',
            'expires_at' => now()->addDays(3),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json($listing->load('images'), 201);
    }

    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'wanted_item' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $listing->update($request->only(['title', 'description', 'wanted_item', 'category_id']));

        return response()->json($listing);
    }

    public function close(Request $request, Listing $listing)
    {
        if ($listing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $listing->update(['status' => 'closed']);
        return response()->json($listing);
    }

    public function renew(Request $request, Listing $listing)
    {
        if ($listing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $listing->update([
            'expires_at' => now()->addDays(3),
            'renewed_count' => $listing->renewed_count + 1,
            'status' => 'open',
        ]);

        return response()->json($listing);
    }
}
