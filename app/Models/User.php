<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar_url',
        'provider',
        'provider_id',
        'is_admin',
        'is_active',
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin && $this->is_active;
    }

    public function networks()
    {
        return $this->hasMany(Network::class, 'owner_id');
    }

    public function networkMembers()
    {
        return $this->hasMany(NetworkMember::class);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }
}
