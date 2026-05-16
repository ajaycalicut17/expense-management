<?php

namespace App\Services\Models;

use App\Models\Expense;

class ExpenseService
{
    public function paginate()
    {
        return Expense::query()
            ->select([
                'id',
                'user_id',
                'category_id',
                'amount',
                'description',
                'spent_at',
            ])
            ->with([
                'user:id,name,email',
                'category:id,name',
            ])
            ->paginate();
    }
}
