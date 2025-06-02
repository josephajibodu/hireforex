<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum BinaryStatus: string implements HasColor, HasLabel
{
    use HasValues;

    case Open = 'open';
    case Close = 'close';


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'success',
            self::Close => 'danger',
        };
    }

    public function getFluxColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'green',
            self::Close => 'red',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Close => 'Close',
        };
    }
}
