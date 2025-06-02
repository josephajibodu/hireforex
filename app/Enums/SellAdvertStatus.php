<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SellAdvertStatus: string implements HasLabel, HasColor
{
    use HasValues;

    case Available = 'available';
    case SoldOut = 'sold-out';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Available => 'Available',
            self::SoldOut => 'Sold Out'
        };
    }

    public function getPublicLabel(): ?string
    {
        return match ($this) {
            self::Available => 'Available',
            self::SoldOut => 'Out of Stock'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Available => 'success',
            self::SoldOut => 'danger'
        };
    }

    public function getFluxColor(): ?string
    {
        return match ($this) {
            self::Available => 'green',
            self::SoldOut => 'red'
        };
    }

    public function getAlternateLabel(): ?string
    {
        return match ($this) {
            self::Available => self::SoldOut->getLabel(),
            self::SoldOut => self::Available->getLabel()
        };
    }
}