<?php

declare(strict_types=1);

use Tests\Support\Constants\ErrorCheckType;
use Tests\Support\Constants\ErrorType;

return [
    [
        'testName' => 'Почта и пароль не заполнены',
        'email' => '',
        'password' => '',
        ErrorCheckType::SEE->value => [
            ErrorType::EMAIL_REQUIRED,
            ErrorType::PASSWORD_REQUIRED,
        ],
        ErrorCheckType::DONT_SEE->value => [
            ErrorType::INVALID_EMAIL,
            ErrorType::INVALID_CREDENTIALS,
        ]
    ],
    [
        'testName' => 'Почта заполнена корректно, а пароль пустой',
        'email' => 'test@mail.ru',
        'password' => '',
        ErrorCheckType::SEE->value => [
            ErrorType::PASSWORD_REQUIRED,
        ],
        ErrorCheckType::DONT_SEE->value => [
            ErrorType::INVALID_EMAIL,
            ErrorType::EMAIL_REQUIRED,
            ErrorType::INVALID_CREDENTIALS,
        ]
    ],
    [
        'testName' => 'Почта пустая, а пароль заполнен корректно',
        'email' => '',
        'password' => '123456',
        ErrorCheckType::SEE->value => [
            ErrorType::EMAIL_REQUIRED,
        ],
        ErrorCheckType::DONT_SEE->value => [
            ErrorType::INVALID_EMAIL,
            ErrorType::INVALID_CREDENTIALS,
            ErrorType::PASSWORD_REQUIRED,
        ]
    ],
    
    [
        'testName' => 'Почта заполнена некорректно, а пароль заполнен корректно',
        'email' => 'test',
        'password' => '123456',
        ErrorCheckType::SEE->value => [
            ErrorType::INVALID_EMAIL,
        ],
        ErrorCheckType::DONT_SEE->value => [
            ErrorType::EMAIL_REQUIRED,
            ErrorType::INVALID_CREDENTIALS,
            ErrorType::PASSWORD_REQUIRED,
        ]
    ],
    [
        'testName' => 'Некорректная пара логин и пароль',
        'email' => 'test@mail.ru',
        'password' => '123456',
        ErrorCheckType::SEE->value => [
            ErrorType::INVALID_CREDENTIALS,
        ],
        ErrorCheckType::DONT_SEE->value => [
            ErrorType::EMAIL_REQUIRED,
            ErrorType::PASSWORD_REQUIRED,
            ErrorType::INVALID_EMAIL,
        ]
    ],
];
