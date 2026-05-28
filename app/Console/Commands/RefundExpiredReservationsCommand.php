<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeliveryRequest;
use Carbon\Carbon;

class RefundExpiredReservationsCommand extends Command
{
    protected $signature = 'reservations:refund';
    protected $description = 'Refund/Cancel pending reservations older than 1 hour';

    public function handle()
    {
        $count = DeliveryRequest::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHour())
            ->update(['status' => 'cancelled']);

        $this->info("Cancelled {$count} expired reservations.");
    }
}
