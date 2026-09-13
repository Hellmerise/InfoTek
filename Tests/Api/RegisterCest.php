<?php

declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;

final class RegisterCest
{
    public function _before(ApiTester $I): void
    {
    
    }
    
    public function registerNewUser(ApiTester $I): void
    {
        $I->wantTo('Успешная регистрация нового пользователя');
        
        $username = $this->uniqueEmail('hercules');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        
        $I->sendPost('/register', [
            'username' => $username,
            'password' => 'secure_pass',
        ]);
        
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Регистрация успешна']);
        $I->seeResponseMatchesJsonType(['message' => 'string']);
    }
    
    public function registerDuplicateUsername(ApiTester $I): void
    {
        $I->wantTo('Ошибка при регистрации с уже существующим username');
        
        $username = $this->uniqueEmail('duplicate');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        
        $I->sendPost('/register', ['username' => $username, 'password' => 'secure_pass']);
        $I->seeResponseCodeIs(201);
        
        $I->sendPost('/register', ['username' => $username, 'password' => 'another_pass']);
        
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.error');
    }
    
    public function registerWithEmptyFields(ApiTester $I): void
    {
        $I->wantTo('Ошибка при регистрации с пустыми полями');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        
        $I->sendPost('/register', ['username' => '', 'password' => '']);
        
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.error');
    }
    
    public function registerAndLogin(ApiTester $I): void
    {
        $I->wantTo('Регистрация и последующий вход с теми же данными');
        
        $username = $this->uniqueEmail('login_test');
        $password = 'secure_pass';
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        
        $I->sendPost('/register', ['username' => $username, 'password' => $password]);
        $I->seeResponseCodeIs(201);
        
        $I->sendPost('/login', ['username' => $username, 'password' => $password]);
        
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(['token' => 'string']);
        $I->seeResponseJsonMatchesJsonPath('$.token');
    }
    
    private function uniqueEmail(string $prefix = 'user'): string
    {
        return sprintf('%s_%s@mail.ru', $prefix, bin2hex(random_bytes(4)));
    }
}
