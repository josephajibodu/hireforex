<?php

namespace App\Filament\Resources\AdminTransferResource\Pages;

use App\Filament\Resources\AdminTransferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminTransfer extends EditRecord
{
    protected static string $resource = AdminTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
