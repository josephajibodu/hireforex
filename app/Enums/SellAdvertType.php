<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SellAdvertType: string implements HasColor, HasLabel
{
    use HasValues;

    case Local = 'local-currency';
    case Usdt = 'usdt';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Local => 'Naira Payment',
            self::Usdt => 'USDT Payment'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Local => 'primary',
            self::Usdt => 'warning'
        };
    }

    public function getFluxColor(): ?string
    {
        return match ($this) {
            self::Local => 'teal',
            self::Usdt => 'amber'
        };
    }
}
