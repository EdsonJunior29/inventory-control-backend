<?php

namespace App\Api\Http\Controllers;

use App\Api\Helper\Pagination\PaginateResponse;
use App\Api\Http\Resources\ProductResource;
use App\Api\Traits\HttpResponses;
use App\Application\UseCases\Products\GetProductById\GetProductById;
use App\Application\UseCases\Products\GetProducts\GetAllProducts;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    use HttpResponses;

    private $getAllProductsUseCases;
    private $getProductByIdUseCases;

    public function __construct(
        GetAllProducts $getAllProducts,
        GetProductById $getProductById
    ) {
        $this->getAllProductsUseCases = $getAllProducts;
        $this->getProductByIdUseCases = $getProductById;
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
        } catch (\Throwable $th) {
            $this->error(
                '',
                $th->getMessage()
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
            $this->error(
                '',
                $th->getMessage()
            );
        }
        
    }
}