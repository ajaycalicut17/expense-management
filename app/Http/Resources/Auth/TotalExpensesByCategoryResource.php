<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TotalExpensesByCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'labels' => $this->resource->pluck('name'),
            'data' => $this->resource->pluck('total')->map(fn ($total) => (float) (($total ?? 0) / 100)),
        ];
    }
}
