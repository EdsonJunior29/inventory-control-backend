<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Api\Http\Middleware\UserAccessValid;
use App\Application\DTOs\Products\ProductInputDto;
use App\Application\UseCases\Products\GetProductById\GetProductById;
use App\Application\UseCases\Products\GetProducts\GetAllProducts;
use App\Application\UseCases\Products\StoreProducts\StoreProduct;
use App\Domain\Entities\Product as EntitiesProduct;
use App\Domain\Exceptions\MinimumQuantityInStockException;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use DateTime;
use PHPUnit\Framework\Attributes\TestWith;
use Illuminate\Http\Response;

# php artisan test --filter=ProductControllerTest
class ProductControllerTest extends TestCase
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

    # php artisan test --filter=ProductControllerTest::test_get_all_products_success
    public function test_get_all_products_success()
    {
        $token = $this->authenticateUser();
        
        $status = Status::factory()->create();
        $category = Category::factory()->create();

        $productsEntity = new EntitiesProduct(
            id: 1,
            name: 'Product 1',
            brand: 'Brand 1',
            category: new \App\Domain\ValueObjects\Category(
                $category->id,
                $category->name
            ),
            description: 'Description 1',
            quantityInStock: 10,
            serialNumber: 'SN001',
            dateOfAcquisition: new DateTime('2023-01-01'),
            status: new \App\Domain\ValueObjects\Status(
                $status->id,
                $status->name
            )
        );

        $products = collect([$productsEntity]);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $products,
            $products->count(),
            15,
            1,
            ['path' => url('/api/products')]
        );

        $getAllProductsUseCases = Mockery::mock(GetAllProducts::class);
        $getAllProductsUseCases->shouldReceive('execute')
            ->andReturn($paginator);

        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });

        //Subtituindo a implemetação pelo mocks
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);
        $this->app->instance(GetAllProducts::class, $getAllProductsUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('products.getAll'));

        //Validação do erro apresentado
        //if ($response->status() !== 200) {
        //    $logContent = [
        //        'status' => $response->status(),
        //        'headers' => $response->headers->all(),
        //        'content' => $response->content()
        //    ];
        //    
        //    file_put_contents(
        //        storage_path('logs/test_error.log'), 
        //        json_encode($logContent, JSON_PRETTY_PRINT)
        //    );
        //    
        //    $this->fail('Erro no teste. Verifique storage/logs/test_error.log');
        //}

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'brand',
                    'category',
                    'description',
                    'quantity_in_stock',
                    'serial_number',
                    'date_of_acquisition',
                    'status',
                ],
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            'links' => ['first', 'last', 'prev', 'next'],
        ]);

        $response->assertJsonFragment([
            'name' => $productsEntity->getName(),
            'brand' => $productsEntity->getBrand(),
            'category' => $productsEntity->getCategory()->getName(),
            'description' => $productsEntity->getDescription(),
            'serial_number' => $productsEntity->getSerialNumber(),
            'status' => $productsEntity->getStatus()->getName(),
        ]);

    }

    # php artisan test --filter=ProductControllerTest::test_it_should_return_empty_when_no_products_exist
    public function test_it_should_return_empty_when_no_products_exist()
    {
        $token = $this->authenticateUser();
        
        $mock = $this->createMock(GetAllProducts::class);
        $mock->method('execute')->willReturn([]);

        $this->app->instance(GetAllProducts::class, $mock);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('products.getAll'));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'status' => true,
            'message' => 'No products found.',
            'data' => [],
        ]);
    }
    
    #[TestWith([1])]
    # php artisan test --filter=ProductControllerTest::test_it_should_return_product_by_id_success
    public function test_it_should_return_product_by_id_success(int $productId)
    {
        $token = $this->authenticateUser();

        $status = Status::factory()->create();
        $category = Category::factory()->create();

        $productEntity = new EntitiesProduct(
            id: $productId,
            name: 'Product 1',
            brand: 'Brand 1',
            category: new \App\Domain\ValueObjects\Category(
                $category->id,
                $category->name
            ),
            description: 'Description 1',
            quantityInStock: 10,
            serialNumber: 'SN001',
            dateOfAcquisition: new DateTime('2023-01-01'),
            status: new \App\Domain\ValueObjects\Status(
                $status->id,
                $status->name
            )
        );

        $getProductByIdUseCaseMock = $this->createMock(GetProductById::class);
        $getProductByIdUseCaseMock
            ->method('execute')
            ->with($productId)
            ->willReturn($productEntity);

        $this->app->instance(GetProductById::class, $getProductByIdUseCaseMock);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('products.getById', ['id' => $productId]));

        $expectedJson = [
            "status" => true,
            "message" => "Product retrieved successfully.",
            "data" => [
                "id" => 1,
                "name" => "Product 1",
                "brand" => "Brand 1",
                "category" => $category->name,
                "description" => "Description 1",
                "quantity_in_stock" => 10,
                "serial_number" => "SN001",
                "date_of_acquisition" => "01-01-2023",
                "status" =>  $status->name,
            ]
        ];

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($expectedJson);
    }

     #[TestWith([1])]
    # php artisan test --filter=ProductControllerTest::test_it_should_return_no_product_by_id
    public function test_it_should_return_no_product_by_id(int $productId)
    {
        $token = $this->authenticateUser();

        $getProductByIdUseCaseMock = $this->createMock(GetProductById::class);
        $getProductByIdUseCaseMock
            ->method('execute')
            ->with($productId)
            ->willReturn(null);

        $this->app->instance(GetProductById::class, $getProductByIdUseCaseMock);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson(route('products.getById', ['id' => $productId]));

        $expectedJson = [
            "status" => true,
            "message" => "No product found.",
            "data" => []
        ];

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($expectedJson);
    }

    # php artisan test --filter=ProductControllerTest::test_store_product_return_success
    public function test_store_product_return_success()
    {
        $token = $this->authenticateUser();

        $category = Category::factory()->create();
        $status = Status::factory()->create();

        $requestData = [
            'name' => 'Product 1',
            'brand' => 'Brand 1',
            'category_id' => $category->id,
            'description' => 'Description 1',
            'date_of_acquisition' => '2025-08-09 10:34:00',
            'quantity_in_stock' => 1000,
            'status_id' => $status->id
        ];

        $productEntity = new EntitiesProduct(
            id: 1,
            name: $requestData['name'],
            brand: $requestData['brand'],
            category: new \App\Domain\ValueObjects\Category(
                $category->id,
                $category->name
            ),
            description: $requestData['description'],
            quantityInStock: $requestData['quantity_in_stock'],
            serialNumber: 'SN001',
            dateOfAcquisition: new DateTime($requestData['date_of_acquisition']),
            status: new \App\Domain\ValueObjects\Status(
                $status->id,
                $status->name
            )
        );

        $productStoreUseCases = Mockery::mock(StoreProduct::class);
        $productStoreUseCases->shouldReceive('execute')
            ->with(Mockery::type(ProductInputDto::class))
            ->andReturn($productEntity);

        $this->app->instance(StoreProduct::class, $productStoreUseCases);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson(env('APP_URL').'/api/products', $requestData);

        $expectedDateOfAcquisition = (new DateTime($requestData['date_of_acquisition']))->format('d-m-Y');
        
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    "id",
                    "name",
                    "brand",
                    "category",
                    "description",
                    "quantity_in_stock",
                    "serial_number",
                    "date_of_acquisition",
                    "status"
                ]
            ])
            ->assertJson([
                "status" => true,
                "message" => "Product successfully created.",
                "data" => [
                    "id" => 1,
                    "name" => $requestData['name'],
                    "brand" => $requestData['brand'],
                    "category" => $category->name,
                    "description" => $requestData['description'],
                    "quantity_in_stock" => $requestData['quantity_in_stock'],
                    "serial_number" => "SN001",
                    "date_of_acquisition" => $expectedDateOfAcquisition,
                    "status" => $status->name
                ]
            ]);

    }

    # php artisan test --filter=ProductControllerTest::test_store_product_throw_exception
    public function test_store_product_throw_exception()
    {
        $token = $this->authenticateUser();

        $category = Category::factory()->create();
        $status = Status::factory()->create();
        
        $requestData = [
            'name' => 'Product 1',
            'brand' => 'Brand 1',
            'category_id' => $category->id,
            'description' => 'Description 1',
            'date_of_acquisition' => '2025-08-09 10:34:00',
            'quantity_in_stock' => 1000,
            'status_id' => $status->id
        ];

        $productStoreUseCases = Mockery::mock(StoreProduct::class);
        $productStoreUseCases->shouldReceive('execute')
            ->with(Mockery::type(ProductInputDto::class))
            ->andThrow(new MinimumQuantityInStockException());

        $this->app->instance(StoreProduct::class, $productStoreUseCases);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson(env('APP_URL').'/api/products', $requestData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => false,
                'message' => 'Minimum acceptable stock quantity is 1',
                'data' => ''
            ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}