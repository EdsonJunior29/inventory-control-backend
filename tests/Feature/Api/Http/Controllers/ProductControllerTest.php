<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Api\Http\Middleware\UserAccessValid;
use App\Application\UseCases\Products\GetProducts\GetAllProducts;
use App\Domain\Entities\Product as EntitiesProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use DateTime;
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
                categoryName: $category->name
            ),
            description: 'Description 1',
            quantityInStock: 10,
            serialNumber: 'SN001',
            dateOfAcquisition: new DateTime('2023-01-01'),
            status: new \App\Domain\ValueObjects\Status(
                statusName: $status->name
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
                    ]
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
                'links' => ['first', 'last', 'prev', 'next'],
            ]
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

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'status' => true,
            'message' => 'No products found.',
            'data' => [],
        ]);
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}