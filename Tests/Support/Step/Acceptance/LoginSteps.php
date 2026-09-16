<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;

use Tests\Support\AcceptanceTester;
use Tests\Support\Constants\LanguageType;
use Tests\Support\Page\LoginPage;
use Tests\Support\Page\RestorePasswordPage;

final class LoginSteps
{
    private LoginPage $loginPage;
    private RestorePasswordPage $restorePasswordPage;
    
    public function __construct(AcceptanceTester $I)
    {
        $this->loginPage = new LoginPage($I);
        $this->restorePasswordPage = new RestorePasswordPage($I);
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
    }
}
