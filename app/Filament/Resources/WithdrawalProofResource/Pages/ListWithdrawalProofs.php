<?php

namespace App\Filament\Resources\WithdrawalProofResource\Pages;

use App\Filament\Resources\WithdrawalProofResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWithdrawalProofs extends ListRecords
{
    protected static string $resource = WithdrawalProofResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
