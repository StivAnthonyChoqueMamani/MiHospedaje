<?php

namespace App\Http\Resources;

use App\JsonApi\Traits\JsonApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    use JsonApiResource;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toJsonApi(): array
    {
        return [
            'name' => $this->resource->name,
            'lastname' => $this->resource->lastname,
            'dni' => $this->resource->dni,
            'observation' => $this->resource->observation,
        ];
    }

}
