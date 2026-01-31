<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CoursePaginationCollection extends ResourceCollection
{
    public $collects = CourseResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                'total' => $this->resource->total(),
                'current' => $this->resource->currentPage(),
                'per_page' => $this->resource->perPage(),
            ]
        ];
    }

    public function paginationInformation($request, $paginated, $default)
    {
        unset($default['links']);
        unset($default['meta']);

        return $default;
    }
}
