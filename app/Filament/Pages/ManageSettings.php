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
                Forms\Components\TextInput::make('bybit_uid')
                    ->label('Bybit UID')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->required(),

                Forms\Components\TextInput::make('usdt_trc')
                    ->label('USDT TRC-20 Address')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->required(),

                Forms\Components\TextInput::make('usdt_bep')
                    ->label('USDT BEP-20 Address')
                    ->inlineLabel()
                    ->maxWidth('4xl')
                    ->required(),
            ])->columns(1)
            ->inlineLabel();
    }
}