<?php

namespace Bites\Core\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;


class LocationResource extends JsonApiResource
{
    // public $type = 'locations';
    /**
     * The resource's attributes.
     */
    // public $attributes = [
    //     'name',
    //     'code',
    //     'description',
    //     'created_at',
    //     'updated_at',
    // ];
    // /**
    //  * The resource's relationships.
    //  */
    public $relationships = [
        'parent'=> LocationResource::class,
        'company'=> CompanyResource::class,
    ];

   public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,

            'hierarchy' => [
                'parent_code' => $this->parent?->code,
            ],

            'company' => $this->whenLoaded(
                'company',
                fn () => CompanyResource::make($this->company)
            ),

            'description' => $this->description,
        ];
    }

    }
