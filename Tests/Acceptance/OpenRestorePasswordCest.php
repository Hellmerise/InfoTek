<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Constants\LanguageType;
use Tests\Support\Step\Acceptance\LoginSteps;

final class OpenRestorePasswordCest
{
    private const int WAIT_TIMEOUT = 5;
    private LoginSteps $loginSteps;
    
    public function _before(AcceptanceTester $I): void
    {
        $this->loginSteps = new LoginSteps($I);
    }
    
    public function openRestorePasswordRu(AcceptanceTester $I): void
    {
        $I->wantTo("[Ru] Открытие страницы восстановления пароля");
        
        $this->openRestorePassword(LanguageType::Russian);
    }
    
    public function openRestorePasswordEn(AcceptanceTester $I): void
    {
        $I->wantTo("[Eng] Открытие страницы восстановления пароля");
        
        $this->openRestorePassword(LanguageType::English);
    }
    
    private function openRestorePassword(LanguageType $language): void
    {
        $this->loginSteps->openRestorePasswordForm(self::WAIT_TIMEOUT, $language);
    }
}
