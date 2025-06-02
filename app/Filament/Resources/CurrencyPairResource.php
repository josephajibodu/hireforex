<?php

namespace App\Filament\Resources;

use App\Enums\BinaryStatus;
use App\Filament\Resources\CurrencyPairResource\Pages;
use App\Filament\Resources\CurrencyPairResource\RelationManagers;
use App\Models\CurrencyPair;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrencyPairResource extends Resource
{
    protected static ?string $model = CurrencyPair::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Main';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('margin')
                    ->required()
                    ->suffix('%')
                    ->numeric(),
                Forms\Components\TextInput::make('trade_duration')
                    ->required()
                    ->suffix('USD')
                    ->numeric(),
                Forms\Components\TextInput::make('daily_capacity')
                    ->required()
                    ->suffix('USD')
                    ->numeric(),
                Forms\Components\TextInput::make('current_capacity')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options(BinaryStatus::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('margin')
                    ->numeric()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => "$state %")
                    ->sortable(),
                Tables\Columns\TextColumn::make('trade_duration')
                    ->label('Duration (hr)')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daily_capacity')
                    ->numeric()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => to_money($state, 1, '$'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_capacity')
                    ->numeric()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => to_money($state, 1, '$'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Status')
                    ->formatStateUsing(fn(CurrencyPair $record) => $record->status->value)
                    ->badge()
                    ->color(fn (CurrencyPair $record): string => $record->status->getColor()),
                Tables\Columns\SelectColumn::make('status')
                    ->label('')
                    ->options(BinaryStatus::class),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCurrencyPairs::route('/'),
            'create' => Pages\CreateCurrencyPair::route('/create'),
            'edit' => Pages\EditCurrencyPair::route('/{record}/edit'),
        ];
    }
}
