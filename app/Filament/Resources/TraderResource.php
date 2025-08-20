<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TraderResource\Pages;
use App\Filament\Resources\TraderResource\RelationManagers;
use App\Models\Trader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TraderResource extends Resource
{
    protected static ?string $model = Trader::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Trading Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Trader Profile')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Trader Nickname'),

                        Forms\Components\TextInput::make('experience_years')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(50)
                            ->label('Years of Experience'),

                        Forms\Components\TextInput::make('favorite_pairs')
                            ->required()
                            ->maxLength(255)
                            ->label('Favorite Trading Pairs')
                            ->placeholder('e.g., EUR/USD, GBP/USD, XAU/USD'),

                        Forms\Components\TextInput::make('track_record')
                            ->required()
                            ->maxLength(5)
                            ->label('Recent Trade Results (Last 5)')
                            ->placeholder('e.g., WLWLW (W=Win, L=Loss)')
                            ->helperText('Enter exactly 5 characters: W for Win, L for Loss'),

                        Forms\Components\TextInput::make('mbg_rate')
                            ->required()
                            ->numeric()
                            ->minValue(80)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('%')
                            ->label('Money-Back Guarantee Rate')
                            ->helperText('Rate between 80% and 100%'),

                        Forms\Components\TextInput::make('potential_return')
                            ->required()
                            ->numeric()
                            ->minValue(110)
                            ->maxValue(200)
                            ->step(0.01)
                            ->suffix('%')
                            ->label('Potential Returns')
                            ->helperText('Expected return percentage (e.g., 130 for 130% return)'),

                        Forms\Components\TextInput::make('min_capital')
                            ->required()
                            ->numeric()
                            ->minValue(10)
                            ->step(0.01)
                            ->prefix('USDT')
                            ->label('Minimum Acceptable Capital'),

                        Forms\Components\TextInput::make('available_volume')
                            ->required()
                            ->numeric()
                            ->minValue(100)
                            ->step(0.01)
                            ->prefix('USDT')
                            ->label('Available Volume (Capacity)'),

                        Forms\Components\TextInput::make('duration_days')
                            ->required()
                            ->numeric()
                            ->minValue(5)
                            ->maxValue(14)
                            ->label('Trading Duration (Days)')
                            ->helperText('Duration between 5 and 14 days'),

                        Forms\Components\Toggle::make('is_available')
                            ->label('Available for Trading')
                            ->helperText('Enable to make this trader visible in the marketplace')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Trader')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('experience_years')
                    ->label('Experience')
                    ->suffix(' years')
                    ->sortable(),

                Tables\Columns\TextColumn::make('favorite_pairs')
                    ->label('Trading Pairs')
                    ->searchable(),

                Tables\Columns\TextColumn::make('track_record')
                    ->label('Track Record')
                    ->formatStateUsing(function ($state) {
                        $result = '';
                        foreach (str_split($state) as $char) {
                            $color = $char === 'W' ? 'success' : 'danger';
                            $result .= "<span class='inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white bg-{$color}-500'>{$char}</span>";
                        }
                        return new \Illuminate\Support\HtmlString($result);
                    }),

                Tables\Columns\TextColumn::make('potential_return')
                    ->label('Returns')
                    ->suffix('%')
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mbg_rate')
                    ->label('MBG Rate')
                    ->suffix('%')
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_capital')
                    ->label('Min Capital')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_volume')
                    ->label('Available Volume')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Duration')
                    ->suffix(' days')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_available')
                    ->options([
                        true => 'Available',
                        false => 'Unavailable',
                    ])
                    ->label('Availability'),

                Tables\Filters\Filter::make('experience_years')
                    ->form([
                        Forms\Components\TextInput::make('min_experience')
                            ->numeric()
                            ->label('Min Experience (Years)'),
                        Forms\Components\TextInput::make('max_experience')
                            ->numeric()
                            ->label('Max Experience (Years)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_experience'],
                                fn (Builder $query, $value): Builder => $query->where('experience_years', '>=', $value),
                            )
                            ->when(
                                $data['max_experience'],
                                fn (Builder $query, $value): Builder => $query->where('experience_years', '<=', $value),
                            );
                    }),

                Tables\Filters\Filter::make('mbg_rate')
                    ->form([
                        Forms\Components\TextInput::make('min_mbg')
                            ->numeric()
                            ->label('Min MBG Rate (%)'),
                        Forms\Components\TextInput::make('max_mbg')
                            ->numeric()
                            ->label('Max MBG Rate (%)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_mbg'],
                                fn (Builder $query, $value): Builder => $query->where('mbg_rate', '>=', $value),
                            )
                            ->when(
                                $data['max_mbg'],
                                fn (Builder $query, $value): Builder => $query->where('mbg_rate', '<=', $value),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_availability')
                    ->label(fn($record) => $record->is_available ? 'Pause' : 'Activate')
                    ->icon(fn($record) => $record->is_available ? 'heroicon-o-pause' : 'heroicon-o-play')
                    ->color(fn($record) => $record->is_available ? 'warning' : 'success')
                    ->action(function($record) {
                        $record->update(['is_available' => !$record->is_available]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Trader availability updated')
                            ->body($record->is_available ? 'Trader is now available' : 'Trader is now paused')
                            ->send();
                    }),
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
            'index' => Pages\ListTraders::route('/'),
            'create' => Pages\CreateTrader::route('/create'),
            'edit' => Pages\EditTrader::route('/{record}/edit'),
        ];
    }
}
