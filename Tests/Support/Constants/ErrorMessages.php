<?php

declare(strict_types=1);

namespace Tests\Support\Constants;

use RuntimeException;

final class ErrorMessages
{
    private const array TRANSLATIONS = [
        LanguageType::Russian->value => [
            ErrorType::INVALID_EMAIL->value         => 'Некорректный email',
            ErrorType::EMAIL_REQUIRED->value        => 'Необходимо заполнить «Электронная почта».',
            ErrorType::PASSWORD_REQUIRED->value     => 'Необходимо заполнить «Пароль».',
            ErrorType::INVALID_CREDENTIALS->value   => 'Некорректный email / пароль',
        ],
        LanguageType::English->value => [
            ErrorType::INVALID_EMAIL->value         => 'Invalid email',
            ErrorType::EMAIL_REQUIRED->value        => 'Email cannot be blank.',
            ErrorType::PASSWORD_REQUIRED->value     => 'Password cannot be blank.',
            ErrorType::INVALID_CREDENTIALS->value   => 'Incorrect email / password',
        ],
    ];
    
    public static function getErrorDescription(ErrorType $type, LanguageType $language): string
    {
        return self::TRANSLATIONS[$language->value][$type->value]
            ?? throw new RuntimeException("Перевод не найден для {$type->value} на языке {$language->value}");
    }
}