<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopUpResource\Pages;
use App\Filament\Resources\TopUpResource\RelationManagers;
use App\Models\TopUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class TopUpResource extends Resource
{
    protected static ?string $model = TopUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationGroup = 'Financial Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Top-Up Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'username')
                            ->searchable()
                            ->required()
                            ->label('User'),

                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('USDT')
                            ->label('Amount'),

                        Forms\Components\Select::make('method')
                            ->options([
                                'bybit' => 'Bybit Transfer',
                                'usdt' => 'USDT Transfer',
                                'binance' => 'Binance Transfer',
                            ])
                            ->required()
                            ->label('Payment Method'),

                        Forms\Components\TextInput::make('bybit_email')
                            ->email()
                            ->maxLength(255)
                            ->label('Email')
                            ->visible(fn (Forms\Get $get) => in_array(($get('method') instanceof \BackedEnum ? $get('method')->value : $get('method')), ['bybit', 'binance'])),

                        Forms\Components\Select::make('network')
                            ->options([
                                'TRC-20' => 'TRC-20',
                                'BEP-20' => 'BEP-20',
                            ])
                            ->label('Network Type')
                            ->visible(fn (Forms\Get $get) => ($get('method') instanceof \BackedEnum ? $get('method')->value : $get('method')) === 'usdt'),

                        Forms\Components\FileUpload::make('screenshot')
                            ->label('Payment Screenshot')
                            ->image()
                            ->directory('topup-screenshots')
                            ->visibility('public'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (USDT)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->label('Payment Method')
                    ->badge()
                    ->color(fn($record) => match($record->method instanceof \BackedEnum ? $record->method->value : $record->method) {
                        'bybit' => 'warning',
                        'usdt' => 'info',
                        'binance' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('bybit_email')
                    ->label('Email')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $method = $record->method instanceof \BackedEnum ? $record->method->value : $record->method;
                        return in_array($method, ['bybit', 'binance']) ? ($state ?: '-') : '-';
                    }),

                Tables\Columns\TextColumn::make('network')
                    ->label('Network')
                    ->badge()
                    ->formatStateUsing(function ($state, $record) {
                        $method = $record->method instanceof \BackedEnum ? $record->method->value : $record->method;
                        return $method === 'usdt' ? ($state ?: '-') : '-';
                    }),

                Tables\Columns\TextColumn::make('screenshot')
                    ->label('Screenshot')
                    ->getStateUsing(function (TopUp $record) {
                        if (! $record->screenshot) return "-";

                        $url = Storage::url($record->screenshot);

                        return new HtmlString("<a class='text-sm underline' href='$url' target='_blank'>View Image</a>");
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($record) => match($record->status instanceof \BackedEnum ? $record->status->value : $record->status) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('method')
                    ->options([
                        'bybit' => 'Bybit Transfer',
                        'usdt' => 'USDT Transfer',
                        'binance' => 'Binance Transfer',
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
                Tables\Actions\Action::make('confirm')
                    ->label('CONFIRM')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn($record) => ($record->status instanceof \BackedEnum ? $record->status->value : $record->status) === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Top-Up')
                    ->modalDescription('Are you sure you want to confirm this top-up request? This will credit the user\'s HireForex balance.')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes (Optional)')
                            ->rows(3),
                    ])
                    ->action(function($record, $data) {
                        app(\App\Actions\ConfirmTopUp::class)->execute($record, $data['admin_notes'] ?? '');

                        Notification::make()
                            ->success()
                            ->title('Top-up confirmed successfully')
                            ->body('User\'s HireForex balance has been credited.')
                            ->send();
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label('REJECT')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn($record) => ($record->status instanceof \BackedEnum ? $record->status->value : $record->status) === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Top-Up')
                    ->modalDescription('Are you sure you want to reject this top-up request?')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->action(function($record, $data) {
                        app(\App\Actions\CancelTopUp::class)->execute($record, $data['admin_notes']);

                        Notification::make()
                            ->success()
                            ->title('Top-up rejected')
                            ->body('User has been notified of the rejection.')
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
            'index' => Pages\ListTopUps::route('/'),
            'create' => Pages\CreateTopUp::route('/create'),
            'view' => Pages\ViewTopUp::route('/{record}'),
            'edit' => Pages\EditTopUp::route('/{record}/edit'),
        ];
    }
}