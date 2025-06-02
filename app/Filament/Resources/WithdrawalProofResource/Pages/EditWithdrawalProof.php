<?php

namespace App\Filament\Resources\WithdrawalProofResource\Pages;

use App\Filament\Resources\WithdrawalProofResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawalProof extends EditRecord
{
    protected static string $resource = WithdrawalProofResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
