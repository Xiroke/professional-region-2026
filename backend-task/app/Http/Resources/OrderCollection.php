<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $statuses = [
            0 => 'ожидает оплаты',
            1 => 'оплачен',
            2 => 'ошибка оплаты',
        ];

        return [
            'id' => $this->pivot->id,
            'payment_status' => $statuses[$this->pivot->payment_status],
            'course' => new CourseResource($this)
        ];
    }
}
