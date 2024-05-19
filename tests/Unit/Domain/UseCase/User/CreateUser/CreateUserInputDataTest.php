<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\CreateUser;

use App\Domain\UseCase\User\CreateUser\CreateUserInputData;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

# php artisan test --filter=CreateUserInputDataTest
class CreateUserInputDataTest extends TestCase
{
    

    # php artisan test --filter=CreateUserInputDataTest::test_constructor
    #[TestWith(['Junior', 'junior@teste.com', 'junior@123456'])]
    #[TestWith(['João', 'joão@teste.com', 'Joaoteste@123456'])]
    public function test_constructor(string $name, string $email, string $password): void
    {
        $createUserInputData = new CreateUserInputData($name, $email, $password);

        $this->assertEquals($name, $createUserInputData->name);
        $this->assertEquals($email, $createUserInputData->email);
        $this->assertEquals($password, $createUserInputData->password);
    }
}