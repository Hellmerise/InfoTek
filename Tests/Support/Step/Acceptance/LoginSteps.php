<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;

use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\LoginPage;
use Tests\Support\Page\RestorePasswordPage;

final class LoginSteps extends AcceptanceTester
{
    private LoginPage $loginPage;
    private RestorePasswordPage $restorePasswordPage;
    
    public function __construct(Scenario $scenario, AcceptanceTester $I)
    {
        parent::__construct($scenario);
        $this->loginPage = new LoginPage($I);
        $this->restorePasswordPage = new RestorePasswordPage($I);
    }
    
    public function getXpathButtonLogin(): string
    {
        return $this->loginPage->getXpathButtonLogin();
    }
    
    public function login(string $email, string $password, bool $isEng = false): void
    {
        $this->openEmptyLoginForm($isEng);
        
        $this->loginPage->fillLogin($email);
        $this->loginPage->fillPassword($password);
        
        $this->loginPage->clickLoginButton();
    }
    
    public function openRestorePasswordForm(bool $isEng, int $timeout): void
    {
        $this->openEmptyLoginForm($isEng);
        
        $this->loginPage->clickRestorePasswordButton();
        $this->restorePasswordPage->seeRestorePasswordPage($timeout);
    }
    
    private function openEmptyLoginForm(bool $isEng): void
    {
        if ($isEng) {
            $this->loginPage->amOnPageEng();
        } else {
            $this->loginPage->amOnPageRu();
        }
        
        $this->loginPage->assertFormIsEmpty();
    }
}
