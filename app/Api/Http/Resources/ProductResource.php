<?php

namespace App\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'brand' => $this->resource->getBrand(),
            'category' => method_exists($this->resource, 'getCategory') && $this->resource->getCategory() !== null
                ? $this->resource->getCategory()->getName()
                : null,
            'description' => $this->resource->getDescription(),
            'quantity_in_stock' => $this->resource->getQuantityInStock(),
            'serial_number' => $this->resource->getSerialNumber(),
            'date_of_acquisition' => $this->resource->getDateOfAcquisition() 
                ? $this->resource->getDateOfAcquisition()->format('d-m-Y') 
                : null,
            'status' => method_exists($this->resource, 'getStatus') && $this->resource->getStatus() !== null
                ? $this->resource->getStatus()->getName()
                : null,
        ];
    }
}