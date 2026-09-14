<?php

declare(strict_types=1);

return [
    [
        'testName' => 'Пустой username',
        'username' => '',
        'password' => 'secure_pass',
    ],
    [
        'testName' => 'Пустой password',
        'username' => 'dmitriy@sheshnikov.ru',
        'password' => '',
    ],
    [
        'testName' => 'Пустые оба поля',
        'username' => '',
        'password' => '',
    ],
];