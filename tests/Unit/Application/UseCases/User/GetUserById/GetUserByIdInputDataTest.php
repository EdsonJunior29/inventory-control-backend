<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\User\GetUserById;

use App\Application\UseCases\User\GetUserById\GetUserInputData;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=GetUserByIdInputDataTest
class GetUserByIdInputDataTest extends TestCase
{
    # php artisan test --filter=GetUserByIdInputDataTest::test_constructor
    #[TestWith([1])]
    public function test_constructor(int $id): void
    {
        $getUserByIdInputData = new GetUserInputData($id);

        $this->assertEquals($id, $getUserByIdInputData->id);
    }
}