<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Example;
use Codeception\Scenario;
use Tests\Support\ApiTester;
use Tests\Support\Step\Api\RegisterSteps;

final class RegisterCest
{
    private RegisterSteps $registerSteps;
    
    public function _before(Scenario $scenario): void
    {
        $this->registerSteps = new RegisterSteps($scenario);
    }
    
    public function registerAndLogin(ApiTester $I): void
    {
        $I->wantTo('Регистрация и последующий вход с теми же данными');
        
        $email    = $this->registerSteps->generateUniqueEmail('login_test');
        $password = 'test123';
        
        $this->seeRegistration($email, $password, true);
        
        $this->registerSteps->login($email, $password);
        
        $this->registerSteps->seeAuthToken();
    }
    
    public function registerDuplicateUsername(ApiTester $I): void
    {
        $I->wantTo('Ошибка при повторной регистрации с тем же email');
        
        $email = $this->registerSteps->generateUniqueEmail('duplicate');
        
        $this->seeRegistration($email, 'test123', true);
        $this->seeRegistration($email, '123test', false);
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function registerWithInvalidData(ApiTester $I, Example $example): void
    {
        $I->wantTo($example['testName']);
        
        $this->seeRegistration($example['username'], $example['password'], false);
    }
    
    private function seeRegistration(string $email, string $password, bool $isSuccess): void
    {
        $this->registerSteps->register($email, $password);
        
        if ($isSuccess) {
            $this->registerSteps->seeSuccessfulRegistration();
        } else {
            $this->registerSteps->seeValidationError();
        }
    }

    private function dataProvider(): array
    {
        return require dirname(__DIR__) . '/Support/Data/Api/RegisterData.php';
    }
}
