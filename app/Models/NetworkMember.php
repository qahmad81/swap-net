<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkMember extends Model
{
    protected $fillable = ['network_id', 'user_id', 'status'];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
