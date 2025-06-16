<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferResource\Pages;
use App\Filament\Resources\TransferResource\RelationManagers;
use App\Models\Transfer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\TransferStatus;

class TransferResource extends Resource
{
    protected static ?string $model = Transfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Main';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('recipient_id')
                    ->relationship('recipient', 'username')
                    ->searchable()
                    ->label('Recipient')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount (USD)')
                    ->numeric()
                    ->minValue(0.01)
                    ->step(0.01)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('From')
                    ->description(fn($record) => $record->user->email),
                Tables\Columns\TextColumn::make('recipient.username')
                    ->label('To')
                    ->description(fn($record) => $record->recipient->email),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (USD)')
                    ->formatStateUsing(fn($state) => number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => TransferStatus::Pending->value,
                        'success' => TransferStatus::Completed->value,
                        'danger' => TransferStatus::Cancelled->value,
                        'primary' => TransferStatus::Refunded->value,
                    ])
                    ->label('Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // No edit action for transfers
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
            'index' => Pages\ListTransfers::route('/'),
//            'create' => Pages\CreateTransfer::route('/create'),
            // 'edit' => Pages\EditTransfer::route('/{record}/edit'),
        ];
    }
}
