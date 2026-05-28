<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryRequest;
use App\Models\Listing;
use App\Models\Offer;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\NotificationService;

class DeliveryController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'offer_id' => 'required|exists:offers,id',
            'requester_address' => 'required|string',
            'offerer_address' => 'required|string',
            'delivery_cost' => 'required|numeric',
            'cost_bearer' => 'required|string',
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        $offer = Offer::findOrFail($request->offer_id);

        if ($offer->status !== 'accepted') {
            return response()->json(['message' => 'Offer must be accepted before creating delivery request'], 422);
        }

        $user = Auth::user();
        if ($user->id !== $listing->user_id && $user->id !== $offer->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $delivery = DeliveryRequest::create($request->all());

        return response()->json($delivery, 201);
    }

    public function show($id)
    {
        $delivery = DeliveryRequest::with(['listing', 'offer'])->findOrFail($id);
        $user = Auth::user();

        // Participants or Network Owner
        $network = Network::find($delivery->listing->network_id);
        
        if ($user->id !== $delivery->listing->user_id && 
            $user->id !== $delivery->offer->user_id && 
            (!$network || $user->id !== $network->owner_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($delivery);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:picked_up,delivered,cancelled',
        ]);

        $delivery = DeliveryRequest::findOrFail($id);
        $listing = Listing::findOrFail($delivery->listing_id);
        $offer = Offer::findOrFail($delivery->offer_id);
        $network = Network::findOrFail($listing->network_id);
        $user = Auth::user();

        if (in_array($request->status, ['picked_up', 'delivered'])) {
            if ($user->id !== $network->owner_id) {
                return response()->json(['message' => 'Only network owner can update to picked_up or delivered'], 403);
            }
        }

        if ($request->status === 'cancelled') {
            if ($delivery->status === 'picked_up') {
                return response()->json(['message' => 'Cannot cancel after items are picked up'], 422);
            }
            if ($user->id !== $listing->user_id && $user->id !== $offer->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $delivery->update(['status' => $request->status]);

        $this->notificationService->notifyDeliveryStatus($delivery);

        return response()->json($delivery);
    }
}