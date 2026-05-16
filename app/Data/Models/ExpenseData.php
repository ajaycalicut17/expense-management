<?php

namespace App\Data\Models;

use Illuminate\Http\Request;

class ExpenseData
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?int $categoryId = null,
        public ?float $amount = null,
        public ?string $description = null,
        public ?string $spentAt = null,
    ) {}

    public static function createFromRequest(Request $request): self
    {
        return new self(
            id: $request->input('id'),
            userId: $request->input('user_id'),
            categoryId: $request->input('category_id'),
            amount: $request->input('amount'),
            description: $request->input('description'),
            spentAt: $request->input('spent_at'),
        );
    }
}
