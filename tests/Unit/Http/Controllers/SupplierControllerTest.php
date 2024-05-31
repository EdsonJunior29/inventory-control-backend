<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Services\SupplierServices\SupplierService;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

# php artisan test --filter=SupplierControllerTest
class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->seed('ProfilesAndUsersSeeder');
    }

    public function authenticateUser()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        $response = $this->postJson(route('auth.login'), [
            'email' => $adminUser->email,
            'password' => 'Teste2@145',
        ]);

        return $response->json('data.token');
    }

    public function test_get_all_suppiers_success(): void
    {
        $token = $this->authenticateUser();

        $suppliersData = Supplier::factory()->count(2)->create();

        $suppliersMock = new LengthAwarePaginator(collect($suppliersData), count($suppliersData), 15, 1);

        $supplierServiceMock = Mockery::mock(SupplierService::class);
        $supplierServiceMock->shouldReceive('getAllSupliers')
            ->andReturn($suppliersMock);

        $this->app->instance(SupplierService::class, $supplierServiceMock);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('suppliers.getAll'));

        $expectedJson = [
            "current_page" => 1,
            'data' => [
                [
                    "id" => $suppliersData[0]->id,
                    "name" => $suppliersData[0]->name
                ],
                [
                    "id" => $suppliersData[1]->id,
                    "name" => $suppliersData[1]->name
                ],
            ],
            "first_page_url" => "http://localhost/api/supplier/all-suplliers?page=1",
            "from" => 1,
            "last_page" => 1,
            "last_page_url" => "http://localhost/api/supplier/all-suplliers?page=1",
            "next_page_url" => null, 
            "path" => "http://localhost/api/supplier/all-suplliers",
            "per_page" => 5,
            "prev_page_url" => null,
            "to" => 2,
            "total" => 2,
        ];

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson($expectedJson);
    }
}