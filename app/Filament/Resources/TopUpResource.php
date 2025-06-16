<?php

namespace App\Filament\Resources;

use App\Enums\TopupStatus;
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

class TopUpResource extends Resource
{
    protected static ?string $model = TopUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('payment_method')
                    ->required(),
                Forms\Components\TextInput::make('bybit_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference')
                    ->maxLength(255),
                Forms\Components\TextInput::make('screenshot_path')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\Textarea::make('rejection_reason')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn($record) => $record->payment_method->getColor()),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('screenshot_path')
                    ->label('Screenshot')
                    ->disk('public')
                    ->height(60)
                    ->width(60),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($record) => match($record->status) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('bybit_email')
                    ->label('Bybit Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn($record) => $record->status === TopupStatus::Pending)
                    ->action(function($record) {
                        $record->status = TopupStatus::Completed;
                        $record->save();
                        $record->user->credit($record->amount, 'Top-up via ' . $record->payment_method->getLabel());

                        Notification::make()
                            ->success()
                            ->title('Topup marked successful')
                            ->send();
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn($record) => $record->status === TopupStatus::Pending)
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->action(function($record, $data) {
                        $record->status = TopupStatus::Cancelled;
                        $record->rejection_reason = $data['rejection_reason'];
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Topup cancelled')
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