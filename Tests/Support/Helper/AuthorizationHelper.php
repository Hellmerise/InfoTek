<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Exception\ModuleException;
use Codeception\Module\Db;

class AuthorizationHelper extends \Codeception\Module
{
    private const array DEFAULT_USER = [
        'email'    => 'dmitriy@sveshnikov.ru',
        'password' => '123test456',
    ];
    
    private const string TABLE_USERS = 'users';
    
    /**
     * @throws ModuleException
     */
    public function haveTestUser(array $overrides = []): int
    {
        /** @var Db $db */
        $db = $this->getModule('Db');
        
        $user = array_merge(self::DEFAULT_USER, $overrides);
        
        $db->haveInDatabase(self::TABLE_USERS, $user);
        
        return (int) $db->grabFromDatabase(self::TABLE_USERS, 'id', ['email' => $user['email']]);
    }
    
    /**
     * @throws ModuleException
     */
    public function grabTestUser(array $criteria = []): ?array
    {
        /** @var Db $db */
        $db = $this->getModule('Db');
        
        if ($criteria === []) {
            $criteria = ['email' => self::DEFAULT_USER['email']];
        }
        
        return $db->grabFromDatabase(self::TABLE_USERS, '*', $criteria);
    }
    
    public function getTableNameUsers(): string
    {
        return self::TABLE_USERS;
    }
    
    public function getTestUser(): array
    {
        return self::DEFAULT_USER;
    }
}
