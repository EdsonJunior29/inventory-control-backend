<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Api\Http\Middleware\UserAccessValid;
use App\Api\Http\Requests\StoreSupplierRequest;
use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Application\DTOs\Suppliers\SupplierOutputDto;
use App\Application\UseCases\Supplier\GetSuppliers\GetAllSupplier;
use App\Application\UseCases\Supplier\StoreSupplier\StoreSupplier;
use App\Application\UseCases\Supplier\UpdateSupplier\UpdateSupplier;
use App\Domain\IRepository\ISupplierRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use App\Domain\Entities\Supplier as EntitiesSupplier;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
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

        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);

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

        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);

        $this->app->instance(GetAllSupplier::class, $getAllSuppliersUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('suppliers.getAll'));

        $expectedJson = [
            "message" => "Suppliers retrieved successfully",
            "data" => [
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
                "current_page" => 1,
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
            ]
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
            'phone' => '(240) 725-5940',
            'cnpj' => '12345678000195'
        ]);

        $supplierEntities = new EntitiesSupplier(
            $supplier->id,
            $supplier->name,
            $supplier->email,
            $supplier->phone,
            $supplier->cnpj
        );

        // Criar um mock do ISupplierRepository usando Mockery
        $supplierRepositoryMock = Mockery::mock(ISupplierRepository::class);
        $supplierRepositoryMock->shouldReceive('getSupplierById')
            ->with($supplierId)
            ->andReturn($supplierEntities);
        
        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);


        // Chamar o método da controller para obter o fornecedor parando parâmetro {id}
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('supplier.getById', ['id' => $supplierId]));

        // Montar o JSON esperado (Retorno da requisição)
        $expectedJson = [
            "message" => "Request was successful.",
            "data" => [
                "id" => $supplier['id'],
                "name" => $supplier['name'],
                "email" => $supplier['email'],
                "phone" => $supplier['phone'],
                "cnpj" => $supplier['cnpj'],
            ]
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
        

        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);

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
        
        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson(route('supplier.deleteById', ['id' => $supplierId]));

        $response->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
    }

    # php artisan test --filter=SupplierControllerTest::test_store_supplier_return_success
    public function test_store_supplier_return_success()
    {
        $token = $this->authenticateUser();

        $requestData = [
            'name' => 'Supplier Name',
            'email' => 'supplier@example.com',
            'phone' => '123456789',
            'cnpj' => '69.337.004/0001-66'
        ];
        
        $supplierOutput = new SupplierOutputDto(
            id: 1,
            name: $requestData['name'],
            email: $requestData['email'],
            phone: $requestData['phone'],
            cnpj: $requestData['cnpj'],
        );

        $storeSupplierUseCases = Mockery::mock(StoreSupplier::class);
        $storeSupplierUseCases->shouldReceive('execute')
            ->with(Mockery::type(SupplierInputDto::class))
            ->andReturn($supplierOutput);

        // Substitui no container
        $this->app->instance(StoreSupplier::class, $storeSupplierUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson(env('APP_URL').'/api/supplier', $requestData);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'cnpj',
            ]
        ])
        ->assertJson([
            'message' => 'Supplier created successfully',
            'data' => [
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'cnpj' => $requestData['cnpj']
            ]
        ]);
    }

    # php artisan test --filter=SupplierControllerTest::test_store_supplier_throw_exception
    public function test_store_supplier_throw_exception()
    {
        $token = $this->authenticateUser();

        $requestData = [
            'name' => 'Supplier Name',
            'email' => 'supplier@example.com',
            'phone' => '123456789',
            'cnpj' => '69.337.004/0001-66'
        ];

        $storeSupplierUseCases = Mockery::mock(StoreSupplier::class);
        $storeSupplierUseCases->shouldReceive('execute')
            ->once()
            ->with(Mockery::type(SupplierInputDto::class))
            ->andThrow(new \Exception('An unexpected error occurred'));

        // Substitua a instância no container do Laravel
        $this->app->instance(StoreSupplier::class, $storeSupplierUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson(env('APP_URL').'/api/supplier', $requestData);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'message' => 'Internal server error: An unexpected error occurred',
                'data' => []
            ]);
    }

    # php artisan test --filter=SupplierControllerTest::test_update_supplier_return_success
    public function test_update_supplier_return_success()
    {
        $token = $this->authenticateUser();

        $supplierId = 1;

        $requestData = [
            'name' => 'Updated Supplier Name',
            'email' => 'teste@teste.com',
            'phone' => '987654321',
        ];

        $updateSupplierUseCases = Mockery::mock(UpdateSupplier::class);
        $updateSupplierUseCases->shouldReceive('execute')
            ->with($supplierId, $requestData)
            ->andReturn(true);
            
        $this->app->instance(UpdateSupplier::class, $updateSupplierUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->putJson(env('APP_URL').'/api/supplier/1', $requestData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Supplier updated successfully',
                'data' => true
            ]);
    }

    # php artisan test --filter=SupplierControllerTest::test_update_supplier_throw_exception
    public function test_update_supplier_throw_exception()
    {
        $token = $this->authenticateUser();

        $supplierId = 1;

        $requestData = [
            'name' => 'Updated Supplier Name',
            'email' => 'teste@teste.com',
            'phone' => '987654321',
        ];
        $updateSupplierUseCases = Mockery::mock(UpdateSupplier::class);
        $updateSupplierUseCases->shouldReceive('execute')
            ->with($supplierId, $requestData)
            ->andThrow(new \Exception('An unexpected error occurred'));

        $this->app->instance(UpdateSupplier::class, $updateSupplierUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->putJson(env('APP_URL').'/api/supplier/1', $requestData);
        
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'message' => 'Internal server error: An unexpected error occurred',
                'data' => []
            ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}