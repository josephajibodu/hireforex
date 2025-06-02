<?php

namespace App\Filament\Resources\CryptoTradeResource\Pages;

use App\Enums\CryptoTradeStatus;
use App\Filament\Resources\CryptoTradeResource;
use App\Models\CryptoTrade;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCryptoTrades extends ListRecords
{
    protected static string $resource = CryptoTradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'pending' => Tab::make()
                ->badge(CryptoTrade::query()->where('status', CryptoTradeStatus::PENDING)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CryptoTradeStatus::PENDING)),
            'completed' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CryptoTradeStatus::COMPLETED)),
            'cancelled' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CryptoTradeStatus::CANCELLED)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pending';
    }
}
