<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['user_id', 'category_id', 'network_id', 'title', 'description', 'type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
