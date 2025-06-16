<?php

namespace App\Filament\Resources\TransferResource\Pages;

use App\Enums\TransferStatus;
use App\Enums\WalletType;
use App\Filament\Resources\TransferResource;
use App\Models\Transfer;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTransfers extends ListRecords
{
    protected static string $resource = TransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle("User account credited")
                ->using(function (array $data) {
                    $user = User::find($data['recipient_id']);

                    $user->credit($data['amount'], "Transfer from Admin");

                    Transfer::query()->create($data);
                })
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = Auth::id();
                    $data['amount'] = $data['amount'] * 100;
                    $data['status'] = TransferStatus::Completed->value;

                    return $data;
                }),
        ];
    }
}
