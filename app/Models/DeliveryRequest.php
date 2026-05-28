<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRequest extends Model
{
    protected $fillable = [
        'listing_id', 
        'offer_id', 
        'requester_address', 
        'offerer_address', 
        'delivery_cost', 
        'cost_bearer', 
        'status'
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}