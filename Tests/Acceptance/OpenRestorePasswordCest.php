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
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->loginSteps = new LoginSteps($scenario, $I);
    }
    
    public function openRestorePasswordRu(AcceptanceTester $I): void
    {
        $this->openRestorePassword($I, LanguageType::Russian);
    }
    
    public function openRestorePasswordEn(AcceptanceTester $I): void
    {
        $this->openRestorePassword($I, LanguageType::English);
    }
    
    private function openRestorePassword(AcceptanceTester $I, LanguageType $language): void
    {
        $isEng = $language === LanguageType::English;
        
        $this->loginSteps->openRestorePasswordForm($isEng, self::WAIT_TIMEOUT);
    }
}
