<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\GetUserByEmail;

use App\Application\UseCases\User\GetUserByEmail\GetUserByEmailInputData;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

# php artisan test --testsuite=Unit --filter=GetUserByEmailInputDataTest
class GetUserByEmailInputDataTest extends TestCase
{
    
    # php artisan test --testsuite=Unit --filter=GetUserByEmailInputDataTest::test_constructor
    #[TestWith(['junior@teste.com'])]
    #[TestWith(['joão@teste.com'])]
    public function test_constructor(string $email): void
    {
        $getUserByEmailInputData = new GetUserByEmailInputData($email);

        $this->assertEquals($email, $getUserByEmailInputData->email);
    }
}