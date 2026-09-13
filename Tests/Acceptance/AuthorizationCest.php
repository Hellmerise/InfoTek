<?php

declare(strict_types=1);

namespace Acceptance;

use Codeception\Example;
use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Constants\ErrorCheckType;
use Tests\Support\Constants\ErrorMessages;
use Tests\Support\Constants\LanguageType;
use Tests\Support\Step\Acceptance\LoginSteps;

final class AuthorizationCest
{
    private const string XPATH_ERROR_PATTERN = '//p[text() = "%s"]';
    private const int WAIT_TIMEOUT           = 5;
    private LoginSteps $loginSteps;
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->loginSteps = new LoginSteps($scenario, $I);
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function negativeAuthorizationRu(AcceptanceTester $I, Example $example): void
    {
        $this->negativeScenarioCest($I, $example, LanguageType::Russian);
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function negativeAuthorizationEn(AcceptanceTester $I, Example $example): void
    {
        $this->negativeScenarioCest($I, $example, LanguageType::English);
    }
    
    private function negativeScenarioCest(AcceptanceTester $I, Example $example, LanguageType $language): void
    {
        $isEng = $language === LanguageType::English;
        
        $I->wantTo($language->label() . ' | ' . $example['testName']);
        
        $this->loginSteps->login($example['email'], $example['password'], $isEng);
        
        $this->checkDescriptionErrors($I, $example, ErrorCheckType::SEE);
        $this->checkDescriptionErrors($I, $example, ErrorCheckType::DONT_SEE);
    }
    
    private function checkDescriptionErrors(AcceptanceTester $I, Example $example, ErrorCheckType $checkType): void
    {
        foreach ($example[$checkType->value] as $errorType) {
            $errorText = ErrorMessages::getErrorDescription($errorType);
            $errorXpath = sprintf(self::XPATH_ERROR_PATTERN, $errorText);
            
            match ($checkType) {
                ErrorCheckType::SEE      => $I->waitForElement($errorXpath, self::WAIT_TIMEOUT),
                ErrorCheckType::DONT_SEE => $I->dontSeeElement($errorXpath),
            };
        }
    }
    
    private function dataProvider(): array
    {
        return require dirname(__DIR__) . '/Support/Data/Acceptance/InvalidAuthorization.php';
    }
}
