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
    
    public function registerNewUser(ApiTester $I): void
    {
        $I->wantTo('Успешная регистрация нового пользователя');
        
        $email = $this->registerSteps->generateUniqueEmail('testUser');
        
        $this->registerSteps->register($email, 'test123');
        $this->registerSteps->seeSuccessfulRegistration();
    }
    
    public function registerDuplicateUsername(ApiTester $I): void
    {
        $I->wantTo('Ошибка при повторной регистрации с тем же email');
        
        $email = $this->registerSteps->generateUniqueEmail('duplicate');
        
        $this->registerSteps->register($email, 'test123');
        $this->registerSteps->seeSuccessfulRegistration();
        
        $this->registerSteps->register($email, '123test');
        $this->registerSteps->seeValidationError();
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function registerWithInvalidData(ApiTester $I, Example $example): void
    {
        $I->wantTo($example['testName']);
        
        $this->registerSteps->register($example['username'], $example['password']);
        $this->registerSteps->seeValidationError();
    }
    
    public function registerAndLogin(ApiTester $I): void
    {
        $I->wantTo('Регистрация и последующий вход с теми же данными');
        
        $email    = $this->registerSteps->generateUniqueEmail('login_test');
        $password = 'test123';
        
        $this->registerSteps->register($email, $password);
        $this->registerSteps->seeSuccessfulRegistration();
        
        $this->registerSteps->login($email, $password);
        $this->registerSteps->seeAuthToken();
    }

    private function dataProvider(): array
    {
        return require dirname(__DIR__) . '/Support/Data/Api/RegisterData.php';
    }
}
