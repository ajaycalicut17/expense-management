<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class TotalExpensesByCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var Collection<int, array{name: string, total: mixed}> $resource */
        $resource = $this->resource;

        return [
            'labels' => $resource->pluck('name'),
            'data' => $resource->pluck('total')->map(fn ($total): float => (float) ((is_numeric($total) ? $total : 0) / 100)),
        ];
    }
}
