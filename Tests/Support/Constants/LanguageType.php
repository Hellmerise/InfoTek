<?php

declare(strict_types=1);

namespace Tests\Support\Constants;

use InvalidArgumentException;

enum LanguageType: string
{
    case English = 'en_US';
    case Russian = 'ru_RU';
    
    public static function fromEnvCode(string $code): self
    {
        return self::tryFrom($code)
            ?? throw new InvalidArgumentException("Неподдерживаемый язык: {$code}");
    }
    
    public function label(): string
    {
        return "[{$this->value}]";
    }
}