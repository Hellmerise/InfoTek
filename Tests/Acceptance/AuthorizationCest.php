<?php

declare(strict_types=1);

namespace Acceptance;

use Codeception\Attribute\Group;
use Codeception\Example;
use Codeception\Exception\TestRuntimeException;
use Codeception\Scenario;
use Facebook\WebDriver\Exception\TimeoutException;
use InvalidArgumentException;
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
    
    public function _before(AcceptanceTester $I): void
    {
        $this->loginSteps = new LoginSteps($I);
    }
    
    public function checkUserWasCreated(AcceptanceTester $I): void
    {
        $I->wantTo('Проверить наличие тестового пользователя в БД');
        
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
        $I->wantTo("Проверка ошибок авторизации на русском языке");
        
        $this->negativeScenarioCest($I, $example, LanguageType::Russian);
    }
    
    /**
     * @dataProvider dataProvider
     */
    #[Group('debug-auth')]
    public function negativeAuthorizationEn(AcceptanceTester $I, Example $example): void
    {
        $I->wantTo("Проверка ошибок авторизации на английском языке");
        
        $this->negativeScenarioCest($I, $example, LanguageType::English);
    }
    
    private function negativeScenarioCest(AcceptanceTester $I, Example $example, LanguageType $language): void
    {
        $this->loginSteps->login($example['email'], $example['password'], $language);
        
        $this->checkDescriptionErrors($I, $example, $language, ErrorCheckType::SEE);
        $this->checkDescriptionErrors($I, $example, $language, ErrorCheckType::DONT_SEE);
        
        try {
            $this->loginSteps->waitForLoginButtonClickable(self::WAIT_TIMEOUT);
        } catch (TimeoutException) {
            throw new TestRuntimeException(sprintf(
                'Кнопка входа осталась некликабельной за %d секунд.' . PHP_EOL .
                'Название теста: %s'  . PHP_EOL .
                'Логин для авторизации: %s'  . PHP_EOL .
                'Пароль: %s',
                self::WAIT_TIMEOUT,
                $example['testName'],
                $example['email'],
                $example['password']));
        }
    }
    
    private function checkDescriptionErrors(AcceptanceTester $I, Example $example, LanguageType $language, ErrorCheckType $checkType): void
    {
        foreach ($example[$checkType->value] as $errorType) {
            $errorText = ErrorMessages::getErrorDescription($errorType, $language);
            $errorXpath = sprintf(self::XPATH_ERROR_PATTERN, $errorText);
            
            match ($checkType) {
                ErrorCheckType::SEE      => $I->waitForElement($errorXpath, self::WAIT_TIMEOUT),
                ErrorCheckType::DONT_SEE => $I->dontSeeElement($errorXpath),
                default => throw new InvalidArgumentException("Неподдерживаемый тип проверки: {$checkType->value}"),
            };
        }
    }
    
    private function dataProvider(): array
    {
        return require dirname(__DIR__) . '/Support/Data/Acceptance/InvalidAuthorization.php';
    }
}
