<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Listing;
use App\Models\DeliveryRequest;
use App\Models\Offer;
use App\Models\Network;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class NotificationCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_expire_listings_command()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $network = Network::factory()->create(['owner_id' => $user->id]);

        $listing = Listing::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'network_id' => $network->id,
            'title' => 'Test Listing',
            'description' => 'Test Description',
            'type' => 'offer',
            'status' => 'open',
            'expires_at' => Carbon::now()->subDay(),
        ]);

        Artisan::call('listings:expire');

        $this->assertEquals('closed', $listing->fresh()->status);
    }

    public function test_refund_expired_reservations()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $network = Network::factory()->create(['owner_id' => $user->id]);
        $listing = Listing::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'network_id' => $network->id,
            'title' => 'Test',
            'description' => 'Test',
            'type' => 'offer',
            'status' => 'open',
        ]);
        $offer = Offer::create([
            'listing_id' => $listing->id,
            'user_id' => User::factory()->create()->id,
            'description' => 'Test',
            'status' => 'accepted',
        ]);

        $delivery = new DeliveryRequest([
            'listing_id' => $listing->id,
            'offer_id' => $offer->id,
            'requester_address' => 'Addr 1',
            'offerer_address' => 'Addr 2',
            'delivery_cost' => 10,
            'cost_bearer' => 'requester',
            'status' => 'pending',
        ]);
        $delivery->created_at = Carbon::now()->subHours(2);
        $delivery->save();

        Artisan::call('reservations:refund');

        $this->assertEquals('cancelled', $delivery->fresh()->status);
    }

    public function test_save_device_token()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->postJson('/api/device-token', [
                'fcm_token' => 'test_token_123'
            ]);

        $response->assertStatus(200);
        $this->assertEquals('test_token_123', $user->fresh()->fcm_token);
    }
}
