<?php

namespace App\Services\Models;

use App\Data\Models\ExpenseData;
use App\Models\Expense;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseService
{
    public function paginate(): LengthAwarePaginator
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
            ->paginate(10);
    }

    public function create(ExpenseData $data): Expense
    {
        $expense = new Expense;
        $expense->user_id = $data->userId;
        $expense->category_id = $data->categoryId;
        $expense->amount = $data->amount;
        $expense->description = $data->description;
        $expense->spent_at = $data->spentAt;
        $expense->save();

        return $expense;
    }
}
