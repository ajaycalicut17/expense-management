<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class AverageDailyExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var int $resource */
        $resource = $this->resource;

        $amount = (float) $resource / 100;

        return [
            // 'average_daily_expenses' => Number::currency($amount),
        ];
    }
}
