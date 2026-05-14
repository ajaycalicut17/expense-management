<?php

namespace App\Models;

use App\Casts\AmountCast;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use HasFactory, SoftDeletes;

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
