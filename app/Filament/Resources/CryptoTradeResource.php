<?php

namespace App\Filament\Resources;

use App\Enums\CryptoTradeStatus;
use App\Enums\WalletType;
use App\Enums\WithdrawalStatus;
use App\Filament\Resources\CryptoTradeResource\Pages;
use App\Filament\Resources\CryptoTradeResource\RelationManagers;
use App\Models\CryptoTrade;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class CryptoTradeResource extends Resource
{
    protected static ?string $model = CryptoTrade::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Main';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'id'),
                Forms\Components\TextInput::make('reference')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('network')
                    ->required(),
                Forms\Components\TextInput::make('wallet_address')

                    ->required(),
                Forms\Components\TextInput::make('comment'),
                Forms\Components\Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Name')
                    ->formatStateUsing(fn (CryptoTrade $record) => "{$record->user->full_name}")
                    ->description(fn (CryptoTrade $record) => "{$record->user->email}")
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => to_money((float) $state, 100, '$'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_sent')
                    ->label("Payable in USDT")
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => to_money((float)$state, 100, hideSymbol: true))
                    ->copyable()
                    ->copyMessage('Amount Copied')
                    ->copyMessageDuration(1500)
                    ->copyableState(fn($state) => $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('network')
                    ->copyable(),
                Tables\Columns\TextColumn::make('wallet_address')
                    ->searchable()->copyable(),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('settle')
                    ->icon('heroicon-o-check')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn(CryptoTrade $record) => $record->isPending())
                    ->action(function (CryptoTrade $record, Tables\Actions\Action $action) {
                        $record->update(['status' => CryptoTradeStatus::COMPLETED]);

                        $action->success();
                    })
                    ->successNotificationTitle('Buy USDT settled successfully'),

                Tables\Actions\Action::make('return')
                    ->icon('heroicon-o-x-mark')
                    ->button()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(CryptoTrade $record) => $record->isPending())
                    ->action(function (CryptoTrade $record, Tables\Actions\Action $action) {
                        return DB::transaction(function () use ($action, $record) {
                            $user = $record->user;

                            $user->credit(WalletType::Withdrawal, $record->amount / 100, "Refund Direct Sale $record->reference");

                            $record->update(['status' => CryptoTradeStatus::CANCELLED]);

                            $action->success();
                        });
                    })
                    ->successNotificationTitle('Buy USDT cancelled and user refunded successfully'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCryptoTrades::route('/'),
            'create' => Pages\CreateCryptoTrade::route('/create'),
        ];
    }
}
