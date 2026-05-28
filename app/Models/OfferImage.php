<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferImage extends Model
{
    protected $fillable = ['offer_id', 'image_path'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
