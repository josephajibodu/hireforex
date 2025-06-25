<?php

namespace App\Filament\Resources;

use App\Actions\AdminDeleteWithdrawal;
use App\Actions\AdminSettleWithdrawal;
use App\Filament\Resources\WithdrawalResource\Pages;
use App\Filament\Resources\WithdrawalResource\RelationManagers;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Financial Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Withdrawal Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('USDT')
                            ->required(),

                        Forms\Components\TextInput::make('fee')
                            ->numeric()
                            ->prefix('USDT')
                            ->required(),

                        Forms\Components\TextInput::make('amount_payable')
                            ->numeric()
                            ->prefix('USDT')
                            ->required(),

                        Forms\Components\Select::make('withdrawal_method')
                            ->options([
                                'usdt_address' => 'USDT Wallet Address',
                                'bybit_uid' => 'Bybit UID',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('usdt_address')
                            ->label('USDT Address')
                            ->visible(fn (Forms\Get $get) => $get('withdrawal_method') === 'usdt_address'),

                        Forms\Components\Select::make('network_type')
                            ->options([
                                'TRC-20' => 'TRC-20',
                                'BEP-20' => 'BEP-20',
                            ])
                            ->visible(fn (Forms\Get $get) => $get('withdrawal_method') === 'usdt_address'),

                        Forms\Components\TextInput::make('bybit_uid')
                            ->label('Bybit UID')
                            ->visible(fn (Forms\Get $get) => $get('withdrawal_method') === 'bybit_uid'),

                        Forms\Components\TextInput::make('reference')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3),

                        Forms\Components\DateTimePicker::make('processed_at')
                            ->label('Processed At'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.phone_number')
                    ->label('Phone Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (USDT)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount_payable')
                    ->label('Amount Payable (USDT)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('withdrawal_method')
                    ->label('Method')
                    ->formatStateUsing(fn (string $state) => $state === 'usdt_address' ? 'USDT Address' : 'Bybit UID')
                    ->badge()
                    ->color(fn (string $state) => $state === 'usdt_address' ? 'info' : 'warning'),

                Tables\Columns\TextColumn::make('destination')
                    ->label('Destination')
                    ->limit(20)
                    ->tooltip(fn (Withdrawal $record) => $record->destination),

                Tables\Columns\TextColumn::make('network_type')
                    ->label('Network')
                    ->badge()
                    ->visible(fn (Withdrawal $record) => $record->withdrawal_method === 'usdt_address'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => ['cancelled', 'rejected'],
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('withdrawal_method')
                    ->options([
                        'usdt_address' => 'USDT Address',
                        'bybit_uid' => 'Bybit UID',
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
                Tables\Actions\Action::make('settle')
                    ->label('SETTLE')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Settle Withdrawal')
                    ->modalDescription('Are you sure you want to mark this withdrawal as completed?')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes (Optional)')
                            ->rows(3),
                    ])
                    ->action(function (Withdrawal $record, array $data) {
                        app(AdminSettleWithdrawal::class)->execute($record, $data['admin_notes'] ?? '');
                    })
                    ->visible(fn (Withdrawal $record) => $record->isPending()),

                Tables\Actions\Action::make('delete')
                    ->label('DELETE')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Withdrawal')
                    ->modalDescription('Are you sure you want to delete this withdrawal? If pending, the user will be refunded.')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes (Optional)')
                            ->rows(3),
                    ])
                    ->action(function (Withdrawal $record, array $data) {
                        app(AdminDeleteWithdrawal::class)->execute($record, $data['admin_notes'] ?? '');
                    })
                    ->visible(fn (Withdrawal $record) => !$record->isCompleted()),

                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListWithdrawals::route('/'),
            'create' => Pages\CreateWithdrawal::route('/create'),
            'edit' => Pages\EditWithdrawal::route('/{record}/edit'),
        ];
    }
}