<?php

namespace App\Infra\Helper\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginateResponse
{
    public static function format(
        LengthAwarePaginator $paginator,
        string $resourceClass,
    ) {
        return [
            'data' => $resourceClass::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];
    }
}