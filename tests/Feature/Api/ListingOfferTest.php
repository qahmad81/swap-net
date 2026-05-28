<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Network;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ListingOfferTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $network;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->network = Network::factory()->create();
        $this->category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
    }

    public function test_create_listing_with_images()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->postJson('/api/listings', [
                'title' => 'Test Listing',
                'description' => 'Test Description',
                'category_id' => $this->category->id,
                'network_id' => $this->network->id,
                'images' => [
                    UploadedFile::fake()->image('listing1.jpg'),
                    UploadedFile::fake()->image('listing2.jpg'),
                ],
            ]);

        $response->assertStatus(201);
        $this->assertCount(2, Listing::first()->images);
        Storage::disk('public')->assertExists(Listing::first()->images[0]->image_path);
    }

    public function test_list_listings_by_network()
    {
        Listing::factory()->count(3)->create([
            'network_id' => $this->network->id,
            'category_id' => $this->category->id,
            'status' => 'open'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/listings?network_id={$this->network->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_create_offer()
    {
        $owner = User::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $owner->id,
            'network_id' => $this->network->id,
            'category_id' => $this->category->id,
            'status' => 'open'
        ]);

        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->postJson('/api/offers', [
                'listing_id' => $listing->id,
                'description' => 'My offer',
                'images' => [
                    UploadedFile::fake()->image('offer1.jpg'),
                ],
            ]);

        $response->assertStatus(201);
        $this->assertCount(1, Offer::first()->images);
    }

    public function test_accept_offer_closes_listing()
    {
        $listing = Listing::factory()->create([
            'user_id' => $this->user->id,
            'network_id' => $this->network->id,
            'category_id' => $this->category->id,
        ]);

        $offerer = User::factory()->create();
        $offer = Offer::create([
            'listing_id' => $listing->id,
            'user_id' => $offerer->id,
            'description' => 'I want this',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/offers/{$offer->id}/accept", ['close_listing' => true]);

        $response->assertStatus(200);
        $this->assertEquals('accepted', $offer->fresh()->status);
        $this->assertEquals('closed', $listing->fresh()->status);
    }

    public function test_cannot_offer_own_listing()
    {
        $listing = Listing::factory()->create([
            'user_id' => $this->user->id,
            'network_id' => $this->network->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/offers', [
                'listing_id' => $listing->id,
                'description' => 'My own listing',
            ]);

        $response->assertStatus(403);
    }

    public function test_renew_listing()
    {
        $listing = Listing::factory()->create([
            'user_id' => $this->user->id,
            'network_id' => $this->network->id,
            'category_id' => $this->category->id,
            'expires_at' => now()->subDay(),
            'status' => 'closed',
            'renewed_count' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/listings/{$listing->id}/renew");

        $response->assertStatus(200);
        $listing->refresh();
        $this->assertTrue($listing->expires_at->isFuture());
        $this->assertEquals(1, $listing->renewed_count);
        $this->assertEquals('open', $listing->status);
    }
}
