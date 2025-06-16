<?php

namespace App\Filament\Resources\TopUpResource\Pages;

use App\Filament\Resources\TopUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTopUp extends ViewRecord
{
    protected static string $resource = TopUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
