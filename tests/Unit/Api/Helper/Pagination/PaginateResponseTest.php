<?php

declare(strict_types=1);

namespace Tests\Unit\Api\Helper\Pagination;

use App\Api\Helper\Pagination\PaginateResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Tests\TestCase;

/**
 * O que é essa classe DummyResource?
 * Ela é um recurso (resource) do Laravel usado para simular como os dados são transformados 
 * no método format() do seu helper PaginateResponse.
 */
class DummyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}

# php artisan test --filter=PaginateResponseTest
class PaginateResponseTest extends TestCase
{
    # php artisan test --filter=PaginateResponseTest::test_formats_length_aware_paginator_correctly
    public function test_formats_length_aware_paginator_correctly()
    {
        // Criar uma coleção de itens dummy
        $items = collect([
            (object) ['id' => 1, 'name' => 'Item 1'],
            (object) ['id' => 2, 'name' => 'Item 2'],
        ]);

        // Criar um LengthAwarePaginator fake
        $paginator = new LengthAwarePaginator(
            $items,
            total: 50,
            perPage: 2,
            currentPage: 1,
            options: ['path' => 'http://localhost/api/items']
        );

        // Chamar o método format usando DummyResource
        $result = PaginateResponse::format($paginator, DummyResource::class);

        // Validar estrutura dos dados retornados
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertArrayHasKey('links', $result);

        // Validar dados formatados no 'data'
        $this->assertInstanceOf(ResourceCollection::class, $result['data']);
        $this->assertCount(2, $result['data']);

        // Validar meta
        $this->assertEquals(1, $result['meta']['current_page']);
        $this->assertEquals(2, $result['meta']['per_page']);
        $this->assertEquals(50, $result['meta']['total']);
        $this->assertEquals(25, $result['meta']['last_page']);

        // Validar links
        $this->assertEquals('http://localhost/api/items?page=1', $result['links']['first']);
        $this->assertEquals('http://localhost/api/items?page=25', $result['links']['last']);
        $this->assertNull($result['links']['prev']);
        $this->assertEquals('http://localhost/api/items?page=2', $result['links']['next']);
    }
}