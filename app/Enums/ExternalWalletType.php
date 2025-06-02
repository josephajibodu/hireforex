<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasLabel;

enum ExternalWalletType: string implements HasLabel
{
    use HasValues;

    case External = 'external';
    case System = 'system';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::External => 'External Source',
            self::System => 'Internal System',
        };
    }
}
