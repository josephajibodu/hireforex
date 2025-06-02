<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CryptoTradeStatus: string implements HasColor, HasLabel
{
    use HasValues;

    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';

    public function getColor(): string
    {
        return match ($this->value) {
            self::PENDING->value => 'warning',
            self::COMPLETED->value => 'success',
            self::CANCELLED->value, self::REJECTED->value => 'danger',
        };
    }

    public function getFluxColor(): string
    {
        return match ($this->value) {
            self::PENDING->value => 'amber',
            self::COMPLETED->value => 'green',
            self::CANCELLED->value, self::REJECTED->value => 'red',
        };
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::PENDING->value => 'Pending',
            self::COMPLETED->value => 'Completed',
            self::CANCELLED->value => 'Cancelled',
            self::REJECTED->value => 'Rejected',
        };
    }
}