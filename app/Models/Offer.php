<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['listing_id', 'user_id', 'description', 'status'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(OfferImage::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
