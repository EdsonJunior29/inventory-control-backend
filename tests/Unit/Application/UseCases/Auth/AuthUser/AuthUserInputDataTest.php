<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Auth\AuthUser;

use App\Application\UseCases\Auth\AuthUser\AuthUserInputData;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=AuthUserInputDataTest
class AuthUserInputDataTest extends TestCase
{
    # php artisan test --filter=AuthUserInputDataTest::test_constructor
    #[TestWith(['junior@teste.com', 'junior@123456'])]
    #[TestWith(['joão@teste.com', 'Joaoteste@123456'])]
    public function test_constructor(string $email, string $password): void
    {
        $createUserInputData = new AuthUserInputData($email, $password);

        $this->assertEquals($email, $createUserInputData->email);
        $this->assertEquals($password, $createUserInputData->password);
    }

}