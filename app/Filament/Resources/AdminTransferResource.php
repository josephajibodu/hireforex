<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminTransferResource\Pages;
use App\Filament\Resources\AdminTransferResource\RelationManagers;
use App\Models\AdminTransfer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AdminTransferResource extends Resource
{
    protected static ?string $model = AdminTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Financial Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transfer Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'username')
                            ->searchable()
                            ->required()
                            ->label('Recipient Username'),

                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(0.01)
                            ->step(0.01)
                            ->prefix('USDT')
                            ->label('Amount to Transfer'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Transfer Notes')
                            ->rows(3)
                            ->placeholder('Reason for this transfer...')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->required()
                            ->default('pending')
                            ->disabled()
                            ->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('admin.username')
                    ->label('Admin')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.username')
                    ->label('Recipient')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (USDT)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('Y-m-d H:i') : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
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
            ->headerActions([
                Tables\Actions\Action::make('transfer_funds')
                    ->label('Transfer Funds')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('username')
                            ->label('Recipient Username')
                            ->required()
                            ->placeholder('Enter username to transfer to'),

                        Forms\Components\TextInput::make('amount')
                            ->label('Amount (USDT)')
                            ->required()
                            ->numeric()
                            ->minValue(0.01)
                            ->step(0.01)
                            ->prefix('USDT')
                            ->placeholder('Enter amount to transfer'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Transfer Notes')
                            ->rows(3)
                            ->placeholder('Reason for this transfer...')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Validate the data
                        if (empty($data['username'])) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Validation Error')
                                ->body('Username is required.')
                                ->send();
                            return;
                        }

                        if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] < 0.01) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Validation Error')
                                ->body('Amount must be at least 0.01 USDT.')
                                ->send();
                            return;
                        }

                        if (empty($data['notes'])) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Validation Error')
                                ->body('Transfer notes are required.')
                                ->send();
                            return;
                        }

                        // Find the user by username
                        $user = \App\Models\User::where('username', $data['username'])->first();
                        if (!$user) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('User Not Found')
                                ->body('The username you entered was not found.')
                                ->send();
                            return;
                        }

                        // Create the admin transfer record
                        $adminTransfer = \App\Models\AdminTransfer::create([
                            'admin_id' => Auth::id(),
                            'user_id' => $user->id,
                            'amount' => $data['amount'],
                            'notes' => $data['notes'],
                            'status' => 'pending',
                        ]);

                        // Process the transfer immediately
                        app(\App\Actions\ProcessAdminTransfer::class)->execute($adminTransfer);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Transfer completed successfully')
                            ->body('User\'s HireForex balance has been credited.')
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Transfer Funds to User')
                    ->modalDescription('Enter the username and amount to transfer USDT to a user\'s HireForex balance.')
                    ->modalSubmitActionLabel('Transfer'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('process')
                    ->label('PROCESS')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn($record) => $record->isPending())
                    ->requiresConfirmation()
                    ->modalHeading('Process Transfer')
                    ->modalDescription('Are you sure you want to process this transfer? This will credit the user\'s HireForex balance.')
                    ->action(function($record) {
                        app(\App\Actions\ProcessAdminTransfer::class)->execute($record);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Transfer processed successfully')
                            ->body('User\'s HireForex balance has been credited.')
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
            'index' => Pages\ListAdminTransfers::route('/'),
        ];
    }
}
