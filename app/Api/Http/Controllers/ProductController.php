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
        $products = $this->getAllProductsUseCases->execute();

        if (empty($products)) {
            return $this->success([], 'Nenhum produto encontrado.');
        }

        return $this->success(
            PaginateResponse::format($products, ProductResource::class),
            'Produtos listados com sucesso.'
        );
    }
}