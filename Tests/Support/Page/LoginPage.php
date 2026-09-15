<?php

declare(strict_types=1);

namespace Tests\Support\Page;

use Tests\Support\AcceptanceTester;
use Tests\Support\Constants\ErrorMessages;
use Tests\Support\Constants\LanguageType;

final class LoginPage
{
    private const string URL = "/login";
    private const string LOGIN_FORM = "//form[@id='login-form']";
    private const string INPUT_LOGIN = self::LOGIN_FORM . "//child::input[@id='loginform-email']";
    private const string INPUT_PASSWORD = self::LOGIN_FORM . "//child::input[@id='loginform-password']";
    private const string BUTTON_LOGIN = self::LOGIN_FORM . "//child::button[text() = 'Войти'] | //child::button[text() = 'Sign In']";
    private const string LINK_RESTORE_ACCESS = self::LOGIN_FORM . "//child::a[text() = 'Забыли пароль']";
    private const string LANGUAGE_RU = "//a[@href='/login?language=ru_RU']";
    private const string LANGUAGE_EN = "//a[@href='/login?language=en_US']";
    private AcceptanceTester $acceptanceTester;
    
    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }
    
    public function getXpathButtonLogin(): string
    {
        return self::BUTTON_LOGIN;
    }
    
    public function amOnPageRu(): void
    {
        ErrorMessages::setLanguage(LanguageType::Russian);
        $this->amOnPage();
    }
    
    public function amOnPageEng(): void
    {
        ErrorMessages::setLanguage(LanguageType::English);
        $this->amOnPage(true);
    }
    
    public function assertFormIsEmpty(): void
    {
        $this->acceptanceTester->seeInField(self::INPUT_LOGIN, '');
        $this->acceptanceTester->seeInField(self::INPUT_PASSWORD, '');
        
        $this->assertPasswordIsMasked();
    }
    
    public function fillLogin(string $email): void
    {
        $this->fillAndVerify(self::INPUT_LOGIN, $email);
    }
    
    public function fillPassword(string $password): void
    {
        $this->fillAndVerify(self::INPUT_PASSWORD, $password);
    }
    
    public function clickLoginButton(): void
    {
        $this->acceptanceTester->click(self::BUTTON_LOGIN);
    }
    
    public function clickRestorePasswordButton(): void
    {
        $this->acceptanceTester->click(self::LINK_RESTORE_ACCESS);
    }
    
    private function amOnPage(bool $isEng = false): void
    {
        $this->acceptanceTester->amOnPage(self::URL);
        $this->acceptanceTester->waitForElementVisible(self::LANGUAGE_EN);
        
        if ($isEng) {
            $this->acceptanceTester->click(self::LANGUAGE_EN);
        } else {
            $this->acceptanceTester->click(self::LANGUAGE_RU);
        }
    }
    
    private function fillAndVerify(string $xpath, string $value): void
    {
        $this->acceptanceTester->fillField($xpath, $value);
        $this->acceptanceTester->seeInField($xpath, $value);
    }
    
    private function assertPasswordIsMasked(): void
    {
        $this->acceptanceTester->seeElement(self::INPUT_PASSWORD, ['type' => 'password']);
    }
}
