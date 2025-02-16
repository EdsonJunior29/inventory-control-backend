<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\User\UpdateUser;

use App\Application\UseCases\User\UpdateUser\UpdateUserInputData;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=UpdateUserInputDataTest
class UpdateUserInputDataTest extends TestCase
{
    # php artisan test --filter=UpdateUserInputDataTest::test_constructor
    #[TestWith([1, 'Junior', 'juniorteste@teste,com'])]
    public function test_constructor(int $id, string $name, string $email): void
    {
        $updateUserInputData = new UpdateUserInputData($id, $name, $email);

        $this->assertEquals($id, $updateUserInputData->id);
        $this->assertEquals($name, $updateUserInputData->name);
        $this->assertEquals($email, $updateUserInputData->email);
    }
}