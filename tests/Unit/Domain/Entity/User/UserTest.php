<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entity\User;

use App\Domain\Entity\User\User;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User(1, 'John Doe', 'john@example.com', 'password123', '2024-04-28 12:00:00', '2024-04-28 12:00:00', '2024-04-28 12:00:00');
    }

    #[TestWith([1 , 'Junior' , 'junior@teste.com', 'senha123456789', '2024-04-28 12:00:00', '2024-04-28 12:00:00', '2024-04-28 12:00:00'])]
    #[TestWith([1 , 'João' , 'joão@teste.com', 'senha123456789', '2024-04-28 12:00:00', '2024-04-28 12:00:00', '2024-04-28 12:00:00'])]
    public function test_constructor(
        int $id,
        string $name,
        string $email,
        string $password,
        string $created_at,
        string $updated_at,
        string $deleted_at,

    ): void
    {
        $user = new User(
            $id,
            $name,
            $email,
            $password,
            $created_at,
            $updated_at,
            $deleted_at,
        );

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($created_at, $user->getCreatedAt());
        $this->assertEquals($updated_at, $user->getUpdatedAt());
        $this->assertEquals($deleted_at, $user->getDeletedAt());
    }

    public function test_get_id() : void
    {
        $this->assertEquals(1, $this->user->getId());
    }

    public function test_get_name() : void
    {
        $this->assertEquals('John Doe', $this->user->getName());
    }

    public function test_get_email() : void
    {
        $this->assertEquals('john@example.com', $this->user->getEmail());
    }

    public function test_get_password() : void
    {
        $this->assertEquals('password123', $this->user->getPassword());
    }

    public function test_get_created_at() : void
    {
        $this->assertEquals('2024-04-28 12:00:00', $this->user->getCreatedAt());
    }

    public function test_get_updated_at() : void
    {
        $this->assertEquals('2024-04-28 12:00:00', $this->user->getUpdatedAt());
    }

    public function test_get_deleted_at() : void
    {
        $this->assertEquals('2024-04-28 12:00:00', $this->user->getDeletedAt());
    }

}
