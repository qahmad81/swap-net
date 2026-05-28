<?php

namespace App\Filament\Admin\Resources\DeliveryRequestResource\Pages;

use App\Filament\Admin\Resources\DeliveryRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryRequests extends ListRecords
{
    protected static string $resource = DeliveryRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
