<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AmountCast;
use App\Concerns\Concerns\Models\Relationships\BelongsToCategory;
use App\Concerns\Models\Relationships\BelongsToUser;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property ?int $user_id
 * @property ?int $category_id
 * @property ?float $amount
 * @property ?Carbon $spent_at
 */
class Expense extends Model
{
    use BelongsToCategory;
    use BelongsToUser;

    /** @use HasFactory<ExpenseFactory> */
    use HasFactory;

    use SoftDeletes;

    #[\Override]
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'category_id' => 'integer',
            'amount' => AmountCast::class,
            'description' => AsStringable::class,
            'spent_at' => 'datetime',
        ];
    }
}
