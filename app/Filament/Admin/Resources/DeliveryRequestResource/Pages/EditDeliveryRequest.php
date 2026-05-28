<?php

namespace App\Filament\Admin\Resources\DeliveryRequestResource\Pages;

use App\Filament\Admin\Resources\DeliveryRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryRequest extends EditRecord
{
    protected static string $resource = DeliveryRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
