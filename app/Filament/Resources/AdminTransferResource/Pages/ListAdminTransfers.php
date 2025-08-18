<?php

namespace App\Filament\Resources\AdminTransferResource\Pages;

use App\Filament\Resources\AdminTransferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminTransfers extends ListRecords
{
    protected static string $resource = AdminTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
