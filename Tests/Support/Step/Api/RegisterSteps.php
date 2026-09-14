<?php

declare(strict_types=1);

namespace Tests\Support\Step\Api;


use Codeception\Scenario;
use Tests\Support\ApiTester;

class RegisterSteps extends ApiTester
{
    private const string JSON_CONTENT_TYPE = 'application/json';
    private const string SUCCESS_MESSAGE   = 'Регистрация успешна';
    
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
    }
    
    public function register(string $username, string $password): void
    {
        $this->haveJsonHeaders();
        $this->sendPost('/register', ['username' => $username, 'password' => $password]);
    }
    
    public function login(string $username, string $password): void
    {
        $this->haveJsonHeaders();
        $this->sendPost('/login', ['username' => $username, 'password' => $password]);
    }
    
    public function seeSuccessfulRegistration(): void
    {
        $this->seeResponseCodeIs(201);
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['message' => self::SUCCESS_MESSAGE]);
    }
    
    public function seeValidationError(): void
    {
        $this->seeResponseCodeIs(400);
        $this->seeResponseIsJson();
        $this->seeResponseJsonMatchesJsonPath('$.error');
    }
    
    public function seeAuthToken(): void
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseIsJson();
        $this->seeResponseJsonMatchesJsonPath('$.token');
        $this->seeResponseMatchesJsonType(['token' => 'string']);
    }
    
    public function generateUniqueEmail(string $prefix = 'user'): string
    {
        $uniqueNumber = mt_rand (100000, 999999);
        $domain       = 'mail.ru';
        
        return $prefix . '_' . $uniqueNumber . '@' . $domain;
    }
    
    private function haveJsonHeaders(): void
    {
        $this->haveHttpHeader('Content-Type', self::JSON_CONTENT_TYPE);
        $this->haveHttpHeader('Accept', self::JSON_CONTENT_TYPE);
    }
}