<?php

namespace App\Api\Http\Controllers;

use App\Api\Helper\Pagination\PaginateResponse;
use App\Api\Http\Resources\ProductResource;
use App\Api\Traits\HttpResponses;
use App\Application\UseCases\Products\GetProducts\GetAllProducts;

class ProductController extends Controller
{
    use HttpResponses;

    private $getAllProductsUseCases;

    public function __construct(GetAllProducts $getAllProducts)
    {
        $this->getAllProductsUseCases = $getAllProducts;
    }

    public function getAllProducts()
    {
        try {
            $products = $this->getAllProductsUseCases->execute();

            if (empty($products)) {
                return $this->success([], 'No products found.');
            }

            return $this->success(
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
}