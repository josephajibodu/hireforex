<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TopupStatus: string implements HasLabel, HasColor
{
    use HasValues;

    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Completed => 'success',
            self::Cancelled => 'danger'
        };
    }

    public function getFluxColor(): ?string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Completed => 'green',
            self::Cancelled => 'red'
        };
    }
}