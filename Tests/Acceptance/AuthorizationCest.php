<?php

declare(strict_types=1);

namespace Acceptance;

use Codeception\Attribute\Group;
use Codeception\Example;
use Codeception\Exception\TestRuntimeException;
use Codeception\Scenario;
use Facebook\WebDriver\Exception\TimeoutException;
use Tests\Support\AcceptanceTester;
use Tests\Support\Constants\ErrorCheckType;
use Tests\Support\Constants\ErrorMessages;
use Tests\Support\Constants\LanguageType;
use Tests\Support\Step\Acceptance\LoginSteps;

final class AuthorizationCest
{
    private const string XPATH_ERROR_PATTERN = '//p[text() = "%s"]';
    private const int WAIT_TIMEOUT           = 1;
    private LoginSteps $loginSteps;
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->loginSteps = new LoginSteps($scenario, $I);
    }
    
    public function checkUserWasCreated(AcceptanceTester $I): void
    {
        $I->wantTo('Проверить наличие тестового пользователя');
        
        $I->haveTestUser();
        
        $tableName = $I->getTableNameUsers();
        $testUser = $I->getTestUser();
        
        $I->seeInDatabase($tableName, ['email' => $testUser['email']]);
    }
    
    /**
     * @dataProvider dataProvider
     */
    #[Group('debug-auth')]
    public function negativeAuthorizationRu(AcceptanceTester $I, Example $example): void
    {
        $this->negativeScenarioCest($I, $example, LanguageType::Russian);
    }
    
    /**
     * @dataProvider dataProvider
     */
    #[Group('debug-auth')]
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
        
        $xpathButtonLogin = $this->loginSteps->getXpathButtonLogin();
        
        try {
            $I->waitForElementClickable($xpathButtonLogin, self::WAIT_TIMEOUT);
        } catch (TimeoutException) {
            throw new TestRuntimeException(sprintf(
                'Кнопка входа осталась некликабельной за %d секунд.' . PHP_EOL .
                'Xpath кнопки: %s'  . PHP_EOL .
                'Название теста: %s'  . PHP_EOL .
                'Логин для авторизации: %s'  . PHP_EOL .
                'Пароль: %s',
                self::WAIT_TIMEOUT,
                $xpathButtonLogin,
                $example['testName'],
                $example['email'],
                $example['password']));
        }
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
