<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasLabel;

enum WalletType: string implements HasLabel
{
    use HasValues;

    case Main = 'main_balance';
    case Reserve = 'reserve_balance';
    case Trading = 'trading_balance';
    case Bonus = 'bonus_balance';
    case Withdrawal = 'withdrawal_balance';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Main => 'Main Balance',
            self::Reserve => 'Reserve Balance',
            self::Trading => 'Trading Balance',
            self::Bonus => 'Bonus Balance',
            self::Withdrawal => 'Withdrawal Balance',
        };
    }
}
