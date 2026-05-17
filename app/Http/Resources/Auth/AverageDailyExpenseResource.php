<?php

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
    public function toArray(Request $request): array
    {
        $amount = (float) (($this->resource ?? 0) / 100);

        return [
            'average_daily_expenses' => Number::currency($amount),
        ];
    }
}
