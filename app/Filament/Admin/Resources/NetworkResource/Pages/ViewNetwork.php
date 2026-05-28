<?php

namespace App\Filament\Admin\Resources\NetworkResource\Pages;

use App\Filament\Admin\Resources\NetworkResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNetwork extends ViewRecord
{
    protected static string $resource = NetworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
