<?php

namespace App\Filament\Resources\AdminTransferResource\Pages;

use App\Filament\Resources\AdminTransferResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAdminTransfer extends CreateRecord
{
    protected static string $resource = AdminTransferResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['admin_id'] = Auth::id();

        return $data;
    }
}
