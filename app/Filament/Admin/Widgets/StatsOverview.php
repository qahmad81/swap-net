<?php

namespace App\Filament\Admin\Widgets;

use App\Models\DeliveryRequest;
use App\Models\Listing;
use App\Models\Network;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Active Listings', Listing::where('status', 'active')->count()),
            Stat::make('Completed Deliveries', DeliveryRequest::where('status', 'completed')->count()),
            Stat::make('Total Networks', Network::count()),
        ];
    }
}
