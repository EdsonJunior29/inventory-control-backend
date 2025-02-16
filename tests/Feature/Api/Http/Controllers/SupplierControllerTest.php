<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Application\UseCases\Supplier\GetSuppliers\GetAllSupplier;
use App\Domain\IRepository\ISupplierRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\TestWith;
use Mockery;
use Tests\TestCase;

# php artisan test --filter=SupplierControllerTest
class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected $supplierRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('ProfilesAndUsersSeeder');
        $this->supplierRepositoryMock = $this->createMock(ISupplierRepository::class);
    }

    public function authenticateUser()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        $response = $this->postJson(env('APP_URL').'/api/login', [
            'email' => $adminUser->email,
            'password' => 'Teste2@145',
        ]);

        return $response->json('data.token');
    }

    # php artisan test --filter=SupplierControllerTest::test_get_all_suppiers_success
    public function test_get_all_suppiers_success(): void
    {
        $token = $this->authenticateUser();

        $suppliersData = Supplier::factory()->count(7)->create();

        $suppliersMock = new LengthAwarePaginator(
            collect($suppliersData->take(5)), // Dados da página atual
            $suppliersData->count(),   // Total de itens
            5,                      // Itens por página
            1,                       // Página atual
            ['path' => url('/api/supplier/all-suppliers')]
        );

        $getAllSuppliersUseCases = Mockery::mock(GetAllSupplier::class);
        $getAllSuppliersUseCases->shouldReceive('execute')
            ->andReturn($suppliersMock);

        $this->app->instance(GetAllSupplier::class, $getAllSuppliersUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('suppliers.getAll'));

        $expectedJson = [
            "current_page" => 1,
            "data" => [
                [
                    "id" => $suppliersData[0]->id,
                    "name" => $suppliersData[0]->name
                ],
                [
                    "id" => $suppliersData[1]->id,
                    "name" => $suppliersData[1]->name
                ],
                [
                    "id" => $suppliersData[2]->id,
                    "name" => $suppliersData[2]->name
                ],
                [
                    "id" => $suppliersData[3]->id,
                    "name" => $suppliersData[3]->name
                ],
                [
                    "id" => $suppliersData[4]->id,
                    "name" => $suppliersData[4]->name
                ],
            ],
            "first_page_url" => "http://localhost/api/supplier/all-suppliers?page=1",
            "from" => 1,
            "last_page" => 2,
            "last_page_url" => "http://localhost/api/supplier/all-suppliers?page=2",
            "links" => [
                [
                    "url" => null,
                    "label" => "&laquo; Previous",
                    "active" => false
                ],
                [
                    "url" => "http://localhost/api/supplier/all-suppliers?page=1",
                    "label" => "1",
                    "active" => true
                ],
                [
                    "url" => "http://localhost/api/supplier/all-suppliers?page=2",
                    "label" => "2",
                    "active" => false
                ],
                [
                    "url" => "http://localhost/api/supplier/all-suppliers?page=2",
                    "label" => "Next &raquo;",
                    "active" => false
                ]
            ],
            "next_page_url" => "http://localhost/api/supplier/all-suppliers?page=2",
            "path" => "http://localhost/api/supplier/all-suppliers",
            "per_page" => 5,
            "prev_page_url" => null,
            "to" => 5,
            "total" => 7
        ];

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($expectedJson);
    }

    #[TestWith([1])]
    # php artisan test --filter=SupplierControllerTest::test_get_supplier_by_id_success
    public function test_get_supplier_by_id_success(int $supplierId)
    {
        // Criar autenticação do usuário
        $token = $this->authenticateUser();

        // Cria factory para adiconar no banco de dados(Para testes)
        $supplier = Supplier::factory()->create([ 
            'id' => $supplierId,
            'name' => 'Supplier Test',
            'email' => 'norval49@example.net',
            'phone' => '(240) 725-5940'
        ]);

        // Criar um mock do ISupplierRepository usando Mockery
        $supplierRepositoryMock = Mockery::mock(ISupplierRepository::class);
        $supplierRepositoryMock->shouldReceive('getSupplierById')
            ->with($supplierId)
            ->andReturn($supplier);
        
        // Chamar o método da controller para obter o fornecedor parando parâmetro {id}
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('supplier.getById', ['id' => $supplierId]));

        // Montar o JSON esperado (Retorno da requisição)
        $expectedJson = [
            "id" => $supplier['id'],
            "name" => $supplier['name'],
            "email" => $supplier['email'],
            "phone" => $supplier['phone']
        ];

        // Verificar a resposta da controller
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($expectedJson);
    }

    #[TestWith([1])]
    # php artisan test --filter=SupplierControllerTest::test_get_supplier_by_id_return_empty
    public function test_get_supplier_by_id_return_empty(int $supplierId)
    {
        // Criar autenticação do usuário
        $token = $this->authenticateUser();

        // Criar um mock do ISupplierRepository usando Mockery
        $supplierRepositoryMock = Mockery::mock(ISupplierRepository::class);
        $supplierRepositoryMock->shouldReceive('getSupplierById')
            ->with($supplierId)
            ->andReturn(null);
        
        // Chamar o método da controller para obter o fornecedor parando parâmetro {id}
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('supplier.getById', ['id' => $supplierId]));

        // Montar o JSON esperado (Retorno da requisição)
        $expectedJson = [];

        // Verificar a resposta da controller
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($expectedJson);
    }

    #[TestWith([1])]
    # php artisan test --filter=SupplierControllerTest::test_delete_supplier_by_id_return_success
    public function test_delete_supplier_by_id_return_success(int $supplierId)
    {
        $token = $this->authenticateUser();

        $supplier = Supplier::factory()->create([ 
            'id' => $supplierId,
            'name' => 'Supplier Test',
            'email' => 'norval49@example.net',
            'phone' => '(240) 725-5940'
        ]);

        $supplierRepositoryMock = Mockery::mock(ISupplierRepository::class);

        $supplierRepositoryMock
            ->shouldReceive('getSupplierById')
            ->with($supplierId)
            ->andReturn($supplier);

        $supplierRepositoryMock
            ->shouldReceive('deleteSupplierById')
            ->with($supplierId)
            ->andReturn(true);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson(route('supplier.deleteById', ['id' => $supplierId]));

        $response->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
    }
}