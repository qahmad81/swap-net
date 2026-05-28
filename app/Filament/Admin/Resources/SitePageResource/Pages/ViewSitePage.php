<?php

namespace App\Filament\Admin\Resources\SitePageResource\Pages;

use App\Filament\Admin\Resources\SitePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSitePage extends ViewRecord
{
    protected static string $resource = SitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
