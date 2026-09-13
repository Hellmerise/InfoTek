<?php

declare(strict_types=1);

namespace Tests\Support\Page;

use GuzzleHttp\Handler\Timeout;
use Tests\Support\AcceptanceTester;

class RestorePasswordPage
{
    private const string URL = "/site/restore-password";
    private const string HEADER_FORM_RESTORE_ACCESS = "//div[text() = 'Password recovery'] | //div[text() = 'Восстановление пароля']";
    
    private AcceptanceTester $acceptanceTester;
    
    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }
    
    public function seeRestorePasswordPage(int $timeout): void
    {
        $this->acceptanceTester->waitForElementVisible(self::HEADER_FORM_RESTORE_ACCESS, $timeout);
        $this->acceptanceTester->seeInCurrentUrl(self::URL);
    }
}