<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Attribute\Examples;
use Codeception\Example;
use Codeception\Scenario;
use Faker\Factory;
use Faker\Generator;
use Tests\Support\ApiTester;
use Tests\Support\Step\Api\RegisterSteps;

final class RegisterCest
{
    private RegisterSteps $registerSteps;
    private Generator $faker;
    
    public function _before(Scenario $scenario): void
    {
        $this->registerSteps = new RegisterSteps($scenario);
        $this->faker = Factory::create();
    }
    
    public function registerAndLogin(ApiTester $I): void
    {
        $I->wantTo('Регистрация и последующий вход с теми же данными');
        
        $email = $this->faker->email();
        $password = $this->faker->password();
        
        $this->seeRegistration($email, $password, true);
        
        $this->registerSteps->login($email, $password);
        
        $this->registerSteps->seeAuthToken();
    }
    
    public function registerDuplicateUsername(ApiTester $I): void
    {
        $I->wantTo('Ошибка при повторной регистрации с тем же email');
        
        $email = $this->faker->email();
        $password = $this->faker->password();
        
        $this->seeRegistration($email, $password, true);
        
        $password_new = $this->faker->password();
        
        $this->seeRegistration($email, $password_new, false);
    }
    
    #[Examples(
        email: '',
        password: 'pass123456',
    )]
    #[Examples(
        email: 'dmitriy@sheshnikov.ru',
        password: '',
    )]
    #[Examples(
        email: '',
        password: '',
    )]
    public function registerWithInvalidData(ApiTester $I, Example $example): void
    {
        $I->wantTo('Ошибка регистрации с недопустимыми данными');
        
        $this->seeRegistration($example['email'], $example['password'], false);
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
}
