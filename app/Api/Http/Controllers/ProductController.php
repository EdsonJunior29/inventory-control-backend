<?php

namespace App\Api\Http\Controllers;

use App\Api\Helper\Pagination\PaginateResponse;
use App\Api\Http\Requests\StoreProductRequest;
use App\Api\Http\Resources\ProductResource;
use App\Api\Traits\HttpResponses;
use App\Application\DTOs\Products\ProductInputDto;
use App\Application\UseCases\Products\GetProductById\GetProductById;
use App\Application\UseCases\Products\GetProducts\GetAllProducts;
use App\Application\UseCases\Products\StoreProducts\StoreProduct;
use DateTime;
use Exception;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    use HttpResponses;

    private $getAllProductsUseCases;
    private $getProductByIdUseCases;
    private $storeProductUseCases;

    public function __construct(
        GetAllProducts $getAllProducts,
        GetProductById $getProductById,
        StoreProduct $storeProduct
    ) {
        $this->getAllProductsUseCases = $getAllProducts;
        $this->getProductByIdUseCases = $getProductById;
        $this->storeProductUseCases = $storeProduct;
    }

    public function getAllProducts()
    {
        try {
            $products = $this->getAllProductsUseCases->execute();

            if (empty($products)) {
                return $this->success(
                    [],
                    'No products found.',
                    Response::HTTP_NOT_FOUND
                );
            }

            return $this->successPaginated(
                PaginateResponse::format($products, ProductResource::class),
                'Products retrieved successfully.'
            );
        } catch (Exception $e) {            
            return $this->error(
                '',
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    public function getProductById(int $productId)
    {
        try {
            $product = $this->getProductByIdUseCases->execute((int) $productId);

            if (empty($product)) {
                return $this->success(
                    [],
                    'No product found.',
                    Response::HTTP_NOT_FOUND
                );
            }

            return $this->success(
                new ProductResource($product),
                'Product retrieved successfully.'
            );

        } catch (\Throwable $th) {
            return $this->error(
                '',
                $th->getMessage()
            );
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $inputDto = new ProductInputDto(
            name: $request->name,
            brand: $request->brand,
            categoryId: $request->category_id,
            quantityInStock: $request->quantity_in_stock,
            dateOfAcquisition: new \DateTime($request->date_of_acquisition),
            statusId: $request->status_id,
            description: $request->description,
        );

        $product = $this->storeProductUseCases->execute($inputDto);

        return $this->create(
            new ProductResource($product),
            'Product successfully created.'
        );

        } catch (\Throwable $th) {
            return $this->error(
                '',
                $th->getMessage(),
                $th->getCode()
            );
        }
    }
}