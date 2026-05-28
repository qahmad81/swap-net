<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;
    protected $fillable = ['owner_id', 'name', 'description', 'is_private', 'slug', 'invite_code', 'qr_data'];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(NetworkMember::class);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
