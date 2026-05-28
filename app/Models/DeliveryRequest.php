<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRequest extends Model
{
    protected $fillable = ['listing_id', 'offer_id', 'network_id', 'status', 'delivery_method', 'tracking_number'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
