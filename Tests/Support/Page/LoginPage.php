<?php

declare(strict_types=1);

namespace Tests\Support\Page;

use Tests\Support\AcceptanceTester;

final class LoginPage
{
    private const string URL = "/login";
    
    private const string LOGIN_FORM = "//form[@id='login-form']";
    private const string INPUT_LOGIN = self::LOGIN_FORM . "//child::input[@id='loginform-email']";
    private const string INPUT_PASSWORD = self::LOGIN_FORM . "//child::input[@id='loginform-password']";
    private const string BUTTON_LOGIN = self::LOGIN_FORM . "//child::button[text() = 'Войти']";
    private const string LINK_RESTORE_ACCESS = self::LOGIN_FORM . "//child::a[text() = 'Забыли пароль']";
    private AcceptanceTester $acceptanceTester;
    
    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }
    
    public function amOnPage(): void
    {
        $this->acceptanceTester->amOnPage(self::URL);
    }
}
