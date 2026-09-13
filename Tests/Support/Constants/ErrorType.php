<?php

declare(strict_types=1);

namespace Tests\Support\Constants;

enum ErrorType: string
{
    case INVALID_EMAIL = 'invalid_email';
    case EMAIL_REQUIRED = 'email_required';
    case PASSWORD_REQUIRED = 'password_required';
    case INVALID_CREDENTIALS = 'invalid_credentials';
}
