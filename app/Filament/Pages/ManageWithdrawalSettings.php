<?php

namespace App\Filament\Pages;

use App\Enums\SystemPermissions;
use App\Settings\WithdrawalSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageWithdrawalSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $settings = WithdrawalSetting::class;

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationGroup = 'Others';

    protected static ?string $title = 'Withdrawal Settings';

    protected static ?string $navigationLabel = 'Withdrawal Settings';

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(SystemPermissions::ManageSettings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Withdrawal Configuration')
                    ->description('Configure withdrawal fees, limits, and processing times')
                    ->schema([
                        Forms\Components\Toggle::make('withdrawal_enabled')
                            ->label('Enable Withdrawals')
                            ->helperText('Enable or disable withdrawal functionality for all users')
                            ->inlineLabel()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('withdrawal_fee_percentage')
                            ->label('Withdrawal Fee Percentage')
                            ->helperText('Percentage fee charged on withdrawals (e.g., 10 for 10%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('%')
                            ->inlineLabel()
                            ->required(),

                        Forms\Components\TextInput::make('minimum_withdrawal_amount')
                            ->label('Minimum Withdrawal Amount')
                            ->helperText('Minimum amount users can withdraw in USDT')
                            ->numeric()
                            ->minValue(0.01)
                            ->step(0.01)
                            ->suffix('USDT')
                            ->inlineLabel()
                            ->required(),

                        Forms\Components\TextInput::make('maximum_withdrawal_amount')
                            ->label('Maximum Withdrawal Amount')
                            ->helperText('Maximum amount users can withdraw in USDT')
                            ->numeric()
                            ->minValue(0.01)
                            ->step(0.01)
                            ->suffix('USDT')
                            ->inlineLabel()
                            ->required(),

                        Forms\Components\TextInput::make('withdrawal_processing_time_hours')
                            ->label('Processing Time (Hours)')
                            ->helperText('Estimated time for withdrawal processing')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(168) // 1 week max
                            ->suffix('hours')
                            ->inlineLabel()
                            ->required(),
                    ])
                    ->columns(2)
                    ->inlineLabel(),
            ])
            ->columns(1);
    }
}