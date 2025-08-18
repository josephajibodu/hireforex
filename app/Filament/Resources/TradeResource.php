<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TradeResource\Pages;
use App\Filament\Resources\TradeResource\RelationManagers;
use App\Models\Trade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TradeResource extends Resource
{
    protected static ?string $model = Trade::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Trading Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Trade Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'username')
                            ->searchable()
                            ->required()
                            ->label('User'),

                        Forms\Components\Select::make('trader_id')
                            ->relationship('trader', 'name')
                            ->searchable()
                            ->required()
                            ->label('Trader'),

                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('USDT')
                            ->label('Investment Amount'),

                        Forms\Components\TextInput::make('potential_return')
                            ->numeric()
                            ->suffix('%')
                            ->disabled()
                            ->label('Potential Return Rate'),

                        Forms\Components\TextInput::make('mbg_rate')
                            ->numeric()
                            ->suffix('%')
                            ->disabled()
                            ->label('MBG Rate'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'refunded' => 'Refunded',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('active'),

                        Forms\Components\DateTimePicker::make('start_date')
                            ->required()
                            ->label('Start Date'),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->label('End Date'),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('trader.name')
                    ->label('Trader')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (USDT)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('potential_return')
                    ->label('Potential Return')
                    ->suffix('%')
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mbg_rate')
                    ->label('MBG Rate')
                    ->suffix('%')
                    ->color('info')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'active',
                        'success' => 'completed',
                        'danger' => 'refunded',
                        'gray' => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d H:i') : '-')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'refunded' => 'Refunded',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_win')
                    ->label('MARK WIN')
                    ->icon('heroicon-o-trophy')
                    ->color('success')
                    ->visible(fn($record) => $record->isActive())
                    ->requiresConfirmation()
                    ->modalHeading('Mark Trade as Win')
                    ->modalDescription('Are you sure you want to mark this trade as a win? The user will receive the full potential return.')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes (Optional)')
                            ->rows(3),
                    ])
                    ->action(function($record, $data) {
                        app(\App\Actions\MarkTradeResult::class)->execute($record, 'win', $data['admin_notes'] ?? '');

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Trade marked as WIN')
                            ->body('User has been credited with the full potential return.')
                            ->send();
                    }),

                Tables\Actions\Action::make('mark_loss')
                    ->label('MARK LOSS')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->isActive())
                    ->requiresConfirmation()
                    ->modalHeading('Mark Trade as Loss')
                    ->modalDescription('Are you sure you want to mark this trade as a loss? The user will receive the MBG refund.')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes (Optional)')
                            ->rows(3),
                    ])
                    ->action(function($record, $data) {
                        app(\App\Actions\MarkTradeResult::class)->execute($record, 'loss', $data['admin_notes'] ?? '');

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Trade marked as LOSS')
                            ->body('User has been credited with the MBG refund.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTrades::route('/'),
            'create' => Pages\CreateTrade::route('/create'),
            'view' => Pages\ViewTrade::route('/{record}'),
            'edit' => Pages\EditTrade::route('/{record}/edit'),
        ];
    }
}
