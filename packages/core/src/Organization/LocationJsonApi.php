<?php

namespace Bites\Core\Organization;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class LocationJsonApi extends JsonApiResource
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
        'parent' => LocationJsonApi::class,
        'company' => CompanyJsonApi::class,
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
                fn () => CompanyJsonApi::make($this->company)
            ),

            'description' => $this->description,
        ];
    }
}
