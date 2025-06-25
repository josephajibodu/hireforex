<?php

namespace App\Filament\Pages;

use App\Enums\SystemPermissions;
use App\Settings\WithdrawalSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;

class ManageWithdrawalSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $settings = WithdrawalSetting::class;

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $title = 'Withdrawal Settings';

    protected static ?string $navigationLabel = 'Withdrawal Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Main Toggle Section
                Forms\Components\Section::make('Withdrawal System')
                    ->description('Enable or disable the withdrawal functionality for all users')
                    ->icon('heroicon-o-power')
                    ->schema([
                        Forms\Components\Toggle::make('withdrawal_enabled')
                            ->label('Enable Withdrawals')
                            ->helperText('When disabled, users will not be able to request withdrawals')
                            ->onIcon('heroicon-s-check-circle')
                            ->offIcon('heroicon-s-x-circle')
                            ->onColor('success')
                            ->offColor('danger')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Fee Configuration Section
                Forms\Components\Section::make('Fee Configuration')
                    ->description('Configure withdrawal fees and charges')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Forms\Components\TextInput::make('withdrawal_fee_percentage')
                            ->label('Withdrawal Fee')
                            ->helperText('Percentage fee charged on all withdrawals (e.g., 10 = 10% fee)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('%')
                            ->prefixIcon('heroicon-o-calculator')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Amount Limits Section
                Forms\Components\Section::make('Amount Limits')
                    ->description('Set minimum and maximum withdrawal amounts')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('minimum_withdrawal_amount')
                                    ->label('Minimum Amount')
                                    ->helperText('Lowest amount users can withdraw')
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->suffix('USDT')
                                    ->prefixIcon('heroicon-o-arrow-down')
                                    ->required(),

                                Forms\Components\TextInput::make('maximum_withdrawal_amount')
                                    ->label('Maximum Amount')
                                    ->helperText('Highest amount users can withdraw')
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->suffix('USDT')
                                    ->prefixIcon('heroicon-o-arrow-up')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Processing Time Section
                Forms\Components\Section::make('Processing Time')
                    ->description('Set expected processing time for withdrawals')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\TextInput::make('withdrawal_processing_time_hours')
                            ->label('Processing Time')
                            ->helperText('Estimated time for withdrawal processing (1-168 hours)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(168) // 1 week max
                            ->suffix('hours')
                            ->prefixIcon('heroicon-o-clock')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ])
            ->columns(1);
    }
}
