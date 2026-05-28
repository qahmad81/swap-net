<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use Carbon\Carbon;

class ExpireListingsCommand extends Command
{
    protected $signature = 'listings:expire';
    protected $description = 'Expire listings that have passed their expiration date';

    public function handle()
    {
        $count = Listing::where('status', 'open')
            ->where('expires_at', '<', Carbon::now())
            ->update(['status' => 'closed']);

        $this->info("Expired {$count} listings.");
    }
}
