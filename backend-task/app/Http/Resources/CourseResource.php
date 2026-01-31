<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "hours" => $this->hours,
            "img" => $this->img,
            "start_date" => $this->start_date->format('d-m-Y'),
            "end_date" => $this->end_date->format('d-m-Y'),
            "price" => $this->price
        ];
    }
}
