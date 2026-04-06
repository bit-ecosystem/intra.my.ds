<?php

namespace Bites\Knowledge\Learning;

use Bites\Service\Resources\StakeHolderResource;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class CourseJsonApi extends JsonApiResource
{
    public $relationships = [
        'modules' => ModuleResource::class,
        'stakeHolder' => StakeHolderResource::class,
    ];

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'status' => $this->status,
            'published_at' => $this->published_at,
        ];
    }
}
