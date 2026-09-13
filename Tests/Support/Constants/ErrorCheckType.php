<?php

declare(strict_types=1);

namespace Tests\Support\Constants;

enum ErrorCheckType: string
{
    case SEE = 'seeErrors';
    case DONT_SEE = 'dontSeeErrors';
}
