<?php

namespace App\Http\Resources;

use App\JsonApi\Traits\JsonApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogbookResource extends JsonResource
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
            'entry_at' => $this->resource->entry_at,
            'exit_at' => $this->resource->exit_at,
            'reservation'  => $this->resource->reservation,
            'observation' => $this->resource->observation,
        ];
    }

    // public function getRelationshipLinks(): array
    // {
    //     return [
    //         'customer', 'user'
    //     ];
    // }

}
