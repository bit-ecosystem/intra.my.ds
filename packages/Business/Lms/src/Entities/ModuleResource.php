<?php

namespace Bites\Business\Lms\Entities;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class ModuleResource extends JsonApiResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'estimated_duration_minutes' => $this->estimated_duration_minutes,
            'validity_months' => $this->validity_months,
            'certificate_template' => $this->certificate_template,
        ];
    }
}
