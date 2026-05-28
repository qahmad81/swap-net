<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Message;
use App\Models\DeliveryRequest;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyNewOffer(Offer $offer)
    {
        $listing = $offer->listing;
        $owner = $listing->user;

        if ($owner->fcm_token) {
            $this->sendPush($owner->fcm_token, 'New Offer', "You received a new offer for {$listing->title}");
        }
    }

    public function notifyOfferAccepted(Offer $offer)
    {
        $buyer = $offer->user;
        $listing = $offer->listing;

        if ($buyer->fcm_token) {
            $this->sendPush($buyer->fcm_token, 'Offer Accepted', "Your offer for {$listing->title} has been accepted!");
        }
    }

    public function notifyNewMessage(Message $message)
    {
        $receiver = $message->receiver;

        if ($receiver->fcm_token) {
            $this->sendPush($receiver->fcm_token, 'New Message', "New message from {$message->sender->name}");
        }
    }

    public function notifyDeliveryStatus(DeliveryRequest $delivery)
    {
        $listing = $delivery->listing;
        $offer = $delivery->offer;

        // Notify both parties? For now, let's notify the one who caused the status change OR both
        // Usually, the requester and offerer need to know
        
        $receivers = [
            $listing->user,
            $offer->user
        ];

        foreach ($receivers as $user) {
            if ($user && $user->fcm_token) {
                $this->sendPush($user->fcm_token, 'Delivery Update', "Delivery status for {$listing->title} updated to: {$delivery->status}");
            }
        }
    }

    protected function sendPush($token, $title, $body)
    {
        try {
            $messaging = Firebase::messaging();
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body));

            $messaging->send($message);
        } catch (\Exception $e) {
            Log::error("FCM Push failed: " . $e->getMessage());
        }
    }
}
