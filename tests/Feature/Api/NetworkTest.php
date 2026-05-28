<?php

namespace Tests\Feature\Api;

use App\Models\Network;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NetworkTest extends TestCase
{
    use RefreshDatabase;

    public function test_network_create()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/networks', [
            'name' => 'Test Network',
            'description' => 'A test network',
            'is_private' => false,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('name', 'Test Network')
            ->assertJsonStructure(['invite_code', 'qr_data']);

        $this->assertDatabaseHas('networks', [
            'name' => 'Test Network',
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseHas('network_members', [
            'user_id' => $user->id,
            'role' => 'admin',
        ]);
    }

    public function test_network_join_via_code()
    {
        $owner = User::factory()->create();
        $network = Network::create([
            'name' => 'Joinable Network',
            'slug' => 'joinable-network',
            'owner_id' => $owner->id,
            'invite_code' => 'ABCDEFGH',
            'qr_data' => 'https://swapnet.app/join/ABCDEFGH'
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson("/api/networks/join/{$network->invite_code}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Joined successfully');

        $this->assertDatabaseHas('network_members', [
            'network_id' => $network->id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);
    }

    public function test_network_leave()
    {
        $owner = User::factory()->create();
        $network = Network::create([
            'name' => 'Leavable Network',
            'slug' => 'leavable-network',
            'owner_id' => $owner->id,
            'invite_code' => 'LEAVE123',
        ]);

        $user = User::factory()->create();
        $network->members()->create([
            'user_id' => $user->id,
            'role' => 'member'
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/networks/{$network->id}/leave");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('network_members', [
            'network_id' => $network->id,
            'user_id' => $user->id,
        ]);
    }
}
