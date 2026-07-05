<?php

declare(strict_types=1);

namespace App\Services\Models;

use App\Data\Filter\DateData;
use App\Data\Models\ExpenseData;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseService
{
    public function paginate(ExpenseData $expenseData): LengthAwarePaginator
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
            ->where('user_id', $expenseData->userId)
            ->paginate(10);
    }

    public function create(ExpenseData $expenseData): Expense
    {
        $expense = new Expense;
        $expense->user_id = $expenseData->userId;
        $expense->category_id = $expenseData->categoryId;
        $expense->amount = $expenseData->amount;
        $expense->description = $expenseData->description;
        $expense->spent_at = $expenseData->spentAt;
        $expense->save();

        return $expense;
    }

    public function update(Expense $expense, ExpenseData $expenseData): Expense
    {
        $expense->category_id = $expenseData->categoryId;
        $expense->amount = $expenseData->amount;
        $expense->description = $expenseData->description;
        $expense->spent_at = $expenseData->spentAt;
        $expense->save();

        return $expense;
    }

    public function averageDailyExpense(
        ExpenseData $expenseData,
        DateData $dateData
    ): ?float {
        return Expense::query()
            ->where('user_id', $expenseData->userId)
            ->whereMonth('spent_at', $dateData->month)
            ->whereYear('spent_at', $dateData->year)
            ->avg('amount');
    }

    public function totalExpensesByCategory(
        ExpenseData $expenseData,
        DateData $dateData
    ): Collection {
        return Expense::query()
            ->select('categories.name')
            ->selectRaw('SUM(amount) as total')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->when($expenseData->userId, fn ($query) => $query->where('user_id', $expenseData->userId))
            ->whereMonth('spent_at', $dateData->month)
            ->whereYear('spent_at', $dateData->year)
            ->groupBy('category_id')
            ->get();
    }
}
