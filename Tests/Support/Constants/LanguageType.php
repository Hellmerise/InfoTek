<?php

declare(strict_types=1);

namespace Tests\Support\Constants;

enum LanguageType: string
{
    case English = 'ENG';
    case Russian = 'РУС';
    
    public function label(): string
    {
        return match ($this) {
            self::English => '[EN]',
            self::Russian => '[RU]',
        };
    }
}