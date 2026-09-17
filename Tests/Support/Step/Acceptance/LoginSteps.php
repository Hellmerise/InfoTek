<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;

use DateTime;
use InvalidArgumentException;
use Tests\Support\AcceptanceTester;
use Tests\Support\Constants\LanguageType;
use Tests\Support\Page\LoginPage;
use Tests\Support\Page\RestorePasswordPage;

final class LoginSteps
{
    private LoginPage $loginPage;
    private RestorePasswordPage $restorePasswordPage;
    private DateTime $dateTime;
    private AcceptanceTester $acceptanceTester;
    
    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        
        $this->loginPage = new LoginPage($this->acceptanceTester);
        $this->restorePasswordPage = new RestorePasswordPage($this->acceptanceTester);
        
        $this->dateTime = new DateTime();
    }
    
    public function waitForLoginButtonClickable(int $timeout): void
    {
        $this->loginPage->waitForLoginButtonClickable($timeout);
    }
    
    public function login(string $email, string $password, LanguageType $language): void
    {
        $this->openEmptyLoginForm($language);
        
        $this->loginPage->fillLogin($email);
        $this->loginPage->fillPassword($password);
        
        $this->loginPage->clickLoginButton();
    }
    
    public function openRestorePasswordForm(int $timeout, LanguageType $language): void
    {
        $this->openEmptyLoginForm($language);
        
        $this->loginPage->clickRestorePasswordButton();
        $this->restorePasswordPage->seeRestorePasswordPage($timeout);
    }
    
    private function openEmptyLoginForm(LanguageType $language): void
    {
        $this->loginPage->amOnPage($language);
        $this->loginPage->assertFormIsEmptyAndPasswordIsMasked();
        $this->seeFooterRight($language);
    }
    
    private function seeFooterRight(LanguageType $language): void
    {
        $current_year = $this->dateTime->format('Y');
        $textRightFooter = $this->loginPage->grabTextFromFooterRight();
        
        $lifetime_company = "2009 - {$current_year}";
        
        $needle = match ($language) {
            LanguageType::Russian => "{$lifetime_company} CRM Автодилер,\nРазработка и поддержка",
            LanguageType::English => "{$lifetime_company} CRM Autodealer,\nDevelopment and support",
            default => throw new InvalidArgumentException(__METHOD__ . " Для языка '{$language->value}' нет заготовленного значения  для проверки"),
        };
        
        $this->acceptanceTester->assertEquals($needle, $textRightFooter);
    }
}
