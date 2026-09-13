<?php

declare(strict_types=1);

namespace Acceptance;

use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Step\Acceptance\LoginSteps;

final class AuthorizationCest
{
    private LoginSteps $loginSteps;
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->loginSteps = new LoginSteps($scenario, $I);
    }
    
    public function negativeAuthorization(AcceptanceTester $I): void
    {
        $I->wantTo("Проверить отображение ошибок на странице авторизации");
        $this->loginSteps->login("", "");
        $I->wait(10);
    }
}
