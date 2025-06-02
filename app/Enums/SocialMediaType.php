<?php

namespace App\Enums;

use App\Traits\HasValues;
use Filament\Support\Contracts\HasLabel;

enum SocialMediaType: string implements HasLabel
{
    use HasValues;

    case WhatsApp = 'whatsapp';
    case Instagram = 'instagram';
    case Twitter = 'twitter';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WhatsApp => 'WhatsApp',
            self::Instagram => 'Instagram',
            self::Twitter => 'X (Twitter)',
        };
    }
}
