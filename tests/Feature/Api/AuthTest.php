<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_social_login_creates_user()
    {
        $abstractUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $abstractUser->method('getId')->willReturn('12345');
        $abstractUser->method('getEmail')->willReturn('test@example.com');
        $abstractUser->method('getName')->willReturn('Test User');
        $abstractUser->method('getAvatar')->willReturn('https://example.com/avatar.jpg');

        $provider = $this->createMock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->method('stateless')->willReturn($provider);
        $provider->method('userFromToken')->willReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->postJson('/api/auth/social-login', [
            'provider' => 'google',
            'token' => 'fake-token',
            'phone' => '123456789'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'provider' => 'google',
            'provider_id' => '12345',
            'phone' => '123456789'
        ]);
    }
}
