<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Listing;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\NotificationService;

class MessageController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'offer_id' => 'required|exists:offers,id',
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        $offer = Offer::findOrFail($request->offer_id);
        $user = Auth::user();

        // Check if user is participant
        if ($user->id !== $listing->user_id && $user->id !== $offer->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = Message::where('listing_id', $listing->id)
            ->where('offer_id', $offer->id)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string',
            'offer_id' => 'nullable|exists:offers,id',
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        $user = Auth::user();

        // Only participants can send messages
        // If offer_id is provided, check against the offer maker
        if ($request->offer_id) {
            $offer = Offer::findOrFail($request->offer_id);
            if ($user->id !== $listing->user_id && $user->id !== $offer->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } else {
            // General inquiry about listing? (Though prompt says listing owner and offer maker conversation)
            // If no offer_id, check if receiver is owner or user is owner
            if ($user->id !== $listing->user_id && $request->receiver_id !== $listing->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $message = Message::create([
            'listing_id' => $request->listing_id,
            'offer_id' => $request->offer_id,
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);

        $this->notificationService->notifyNewMessage($message);

        return response()->json($message, 201);
    }

    public function markRead($id)
    {
        $message = Message::findOrFail($id);
        
        if (Auth::id() !== $message->receiver_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->update(['read_at' => now()]);

        return response()->json(['message' => 'Message marked as read']);
    }
}