<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Network;
use App\Models\Offer;
use App\Models\User;
use App\Models\Message;
use App\Models\DeliveryRequest;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingDeliveryReviewTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $offerer;
    protected $network;
    protected $category;
    protected $listing;
    protected $offer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->create();
        $this->offerer = User::factory()->create();
        $this->network = Network::factory()->create(['owner_id' => User::factory()->create()->id]);
        $this->category = Category::create(['name' => 'Test', 'slug' => 'test']);
        
        $this->listing = Listing::factory()->create([
            'user_id' => $this->owner->id,
            'network_id' => $this->network->id,
            'category_id' => $this->category->id,
        ]);

        $this->offer = Offer::create([
            'listing_id' => $this->listing->id,
            'user_id' => $this->offerer->id,
            'description' => 'Test offer',
            'status' => 'pending'
        ]);
    }

    public function test_send_and_read_messages()
    {
        // Owner sends message to offerer
        $response = $this->actingAs($this->owner)
            ->postJson('/api/messages', [
                'listing_id' => $this->listing->id,
                'offer_id' => $this->offer->id,
                'receiver_id' => $this->offerer->id,
                'body' => 'Hello from owner',
            ]);

        $response->assertStatus(201);
        $message = Message::first();
        $this->assertEquals('Hello from owner', $message->body);

        // Offerer reads message
        $response = $this->actingAs($this->offerer)
            ->postJson("/api/messages/{$message->id}/read");

        $response->assertStatus(200);
        $this->assertNotNull($message->fresh()->read_at);

        // Unauthorized user cannot read
        $other = User::factory()->create();
        $response = $this->actingAs($other)
            ->postJson("/api/messages/{$message->id}/read");
        $response->assertStatus(403);
    }

    public function test_create_delivery_request()
    {
        $this->offer->update(['status' => 'accepted']);

        $response = $this->actingAs($this->owner)
            ->postJson('/api/deliveries', [
                'listing_id' => $this->listing->id,
                'offer_id' => $this->offer->id,
                'requester_address' => '123 Main St',
                'offerer_address' => '456 Elm St',
                'delivery_cost' => 15.00,
                'cost_bearer' => 'requester',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('delivery_requests', [
            'listing_id' => $this->listing->id,
            'offer_id' => $this->offer->id,
            'status' => 'pending'
        ]);
    }

    public function test_update_delivery_status()
    {
        $this->offer->update(['status' => 'accepted']);
        $delivery = DeliveryRequest::create([
            'listing_id' => $this->listing->id,
            'offer_id' => $this->offer->id,
            'requester_address' => '123 Main St',
            'offerer_address' => '456 Elm St',
            'delivery_cost' => 15.00,
            'cost_bearer' => 'requester',
        ]);

        $networkOwner = User::find($this->network->owner_id);

        // Network owner updates to picked_up
        $response = $this->actingAs($networkOwner)
            ->putJson("/api/deliveries/{$delivery->id}/status", [
                'status' => 'picked_up'
            ]);

        $response->assertStatus(200);
        $this->assertEquals('picked_up', $delivery->fresh()->status);

        // Owner (participant) cannot update to delivered
        $response = $this->actingAs($this->owner)
            ->putJson("/api/deliveries/{$delivery->id}/status", [
                'status' => 'delivered'
            ]);
        $response->assertStatus(403);
    }

    public function test_create_review_after_delivery()
    {
        $this->offer->update(['status' => 'accepted']);
        $delivery = DeliveryRequest::create([
            'listing_id' => $this->listing->id,
            'offer_id' => $this->offer->id,
            'requester_address' => '123 Main St',
            'offerer_address' => '456 Elm St',
            'delivery_cost' => 15.00,
            'cost_bearer' => 'requester',
            'status' => 'delivered'
        ]);

        $response = $this->actingAs($this->offerer)
            ->postJson('/api/reviews', [
                'listing_id' => $this->listing->id,
                'reviewed_id' => $this->owner->id,
                'rating' => 5,
                'comment' => 'Great trade!'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reviews', [
            'listing_id' => $this->listing->id,
            'reviewer_id' => $this->offerer->id,
            'rating' => 5
        ]);
    }

    public function test_cannot_review_without_delivery()
    {
        $response = $this->actingAs($this->offerer)
            ->postJson('/api/reviews', [
                'listing_id' => $this->listing->id,
                'reviewed_id' => $this->owner->id,
                'rating' => 5,
            ]);

        $response->assertStatus(422);
    }
}