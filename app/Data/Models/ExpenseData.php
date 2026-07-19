<?php

declare(strict_types=1);

namespace App\Data\Models;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class ExpenseData
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?int $categoryId = null,
        public ?float $amount = null,
        public ?string $description = null,
        public ?Carbon $spentAt = null,
    ) {}

    public static function createFromRequest(Request $request): self
    {
        return new self(
            id: $request->integer('id'),
            userId: Gate::allows('viewAny', User::class) ? $request->integer('user_id') : $request->user()?->id,
            categoryId: $request->integer('category_id'),
            amount: $request->float('amount'),
            description: $request->input('description'),
            spentAt: $request->date('spent_at'),
        );
    }
}
