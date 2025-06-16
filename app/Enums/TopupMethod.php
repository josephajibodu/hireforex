<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TopupMethod: string implements HasLabel, HasColor
{
    use HasValues;

    case ByBit = 'bybit';
    case USDT = 'usdt';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ByBit => 'ByBit',
            self::USDT => 'USDT',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ByBit => 'warning',
            self::USDT => 'success',
        };
    }

    public function getFluxColor(): ?string
    {
        return match ($this) {
            self::ByBit => 'amber',
            self::USDT => 'green',
        };
    }
}