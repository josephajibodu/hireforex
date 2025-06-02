<?php

namespace App\Filament\Pages;

use App\Enums\Permissions;
use App\Enums\SystemPermissions;
use App\Settings\GeneralSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Support\RawJs;

class ManageSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSetting::class;

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationGroup = 'Others';

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(SystemPermissions::ManageSettings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('referral_type')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->live()
                    ->options([
                        'percentage' => 'Percentage',
                        'flat' => 'Flat'
                    ])
                    ->afterStateUpdated(fn(Forms\Set $set) => $set('referral_bonus', ''))
                    ->required(),

                Forms\Components\TextInput::make('referral_bonus')
                    ->inlineLabel()
                    ->prefix(fn(Forms\Get $get) => $get('referral_type') === 'flat' ? '₦' : '')
                    ->suffix(fn(Forms\Get $get) => $get('referral_type') === 'flat' ? '' : '%')
                    ->maxWidth('4xl')
                    ->helperText(fn(Forms\Get $get) => $get('referral_type') === 'flat' ? 'Enter the amount in Naira' : 'Enter the amount in % e.g 20, 30, ... 100')
                    ->minValue(1)
                    ->maxValue(fn(Forms\Get $get) => $get('referral_type') === 'flat' ? null : 100)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('withdrawal_fee')
                    ->label('Withdrawal Fee in Percentage')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->postfix("%")
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0.1)
                    ->maxValue(50)
                    ->required(),

                Forms\Components\TextInput::make('order_time_limit')
                    ->label('Order Time Limit (Minutes)')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('reserve_balance_duration')
                    ->label('How long fund stays in reserve (Minutes)')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->numeric()
                    ->minValue(0)
                    ->suffix('minutes')
                    ->required(),

                Forms\Components\TextInput::make('usd_rate')
                    ->label('USD Exchange Rate')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                Forms\Components\Toggle::make('enable_registration_bonus')
                    ->label('Enable Registration Bonus')
                    ->inlineLabel()
                    ->live(),

                Forms\Components\TextInput::make('registration_bonus')
                    ->label('Registration Bonus Amount')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->prefix('USD')
                    ->numeric()
                    ->minValue(0),

                Forms\Components\TextInput::make('minimum_usdt_withdrawal')
                    ->label('Minimum USDT Withdrawal')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->prefix('USD')
                    ->numeric()
                    ->minValue(0),

                Forms\Components\TextInput::make('usdt_withdrawal_fee')
                    ->label('USDT Withdrawal Fee')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->postfix("%")
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0.1)
                    ->maxValue(50)
                    ->minValue(0),

                Forms\Components\TextInput::make('minimum_trade_amount_on_reg')
                    ->label('Minimum Trade Amount for Bonus')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->prefix('USD')
                    ->numeric()
                    ->minValue(0),

                Forms\Components\Repeater::make('community_links')
                    ->label('Community Links')
                    ->maxWidth('4xl')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Platform Name')
                            ->required(),
                        Forms\Components\TextInput::make('label')
                            ->label('Label')
                            ->helperText('This will be the clickable text')
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->required(),
                    ])
                    ->collapsible()
                    ->columns(2)
            ])->columns(1)
            ->inlineLabel();
    }
}
