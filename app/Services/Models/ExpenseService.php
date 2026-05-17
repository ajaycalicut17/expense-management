<?php

namespace App\Services\Models;

use App\Data\Filter\DateData;
use App\Data\Models\ExpenseData;
use App\Models\Expense;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseService
{
    public function paginate(ExpenseData $data): LengthAwarePaginator
    {
        return Expense::query()
            ->select([
                'id',
                'category_id',
                'amount',
                'description',
                'spent_at',
            ])
            ->with([
                'category:id,name',
            ])
            ->where('user_id', $data->userId)
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

    public function update(Expense $expense, ExpenseData $data): Expense
    {
        $expense->category_id = $data->categoryId;
        $expense->amount = $data->amount;
        $expense->description = $data->description;
        $expense->spent_at = $data->spentAt;
        $expense->save();

        return $expense;
    }

    public function averageDailyExpense(
        ExpenseData $expense,
        DateData $date
    ): ?float {
        return Expense::query()
            ->where('user_id', $expense->userId)
            ->whereMonth('spent_at', $date->month)
            ->whereYear('spent_at', $date->year)
            ->avg('amount');
    }
}
