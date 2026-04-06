<?php

namespace Bites\Service\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class StakeHolderResource extends JsonApiResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'role_id' => $this->role_id,
            'can_view' => $this->can_view,
            'can_edit' => $this->can_edit,
        ];
    }
}
