<?php

declare(strict_types=1);

namespace Support\Step\Acceptance;

use Codeception\Scenario;
use Support\AcceptanceTester;
use Support\Page\LoginPage;

final class LoginSteps extends AcceptanceTester
{
    private LoginPage $loginPage;
    
    public function __construct(Scenario $scenario, AcceptanceTester $I)
    {
        parent::__construct($scenario);
        $this->loginPage = new LoginPage($I);
    }
    
    public function login(string $username, string $password): void
    {
        $this->loginPage->amOnPage();
    }
}
