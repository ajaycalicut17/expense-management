<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Data\Models\ExpenseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Expense\StoreExpenseRequest;
use App\Http\Requests\Auth\Expense\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\Models\CategoryService;
use App\Services\Models\ExpenseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(ExpenseService $expenseService, ExpenseData $expenseData): View
    {
        $expenseData->userId = Auth::id();
        $lengthAwarePaginator = $expenseService->paginate($expenseData);

        return view('auth.expense.index', ['expenses' => $lengthAwarePaginator]);
    }

    public function create(CategoryService $categoryService): View
    {
        $categories = Cache::rememberForever('expenses.categories', fn (): Collection => $categoryService->all());

        return view('auth.expense.create', ['categories' => $categories]);
    }

    public function store(
        StoreExpenseRequest $storeExpenseRequest,
        ExpenseService $expenseService
    ): RedirectResponse {
        $expenseData = ExpenseData::createFromRequest($storeExpenseRequest);
        $expenseData->userId = $storeExpenseRequest->user()->id;

        $expenseService->create($expenseData);

        return to_route('expense.index')->with('status', 'Expense added successfully');
    }

    public function show(
        Expense $expense,
        CategoryService $categoryService
    ): View {
        $categories = Cache::rememberForever('expenses.categories', fn (): Collection => $categoryService->all());

        return view('auth.expense.show', ['expense' => $expense, 'categories' => $categories]);
    }

    public function edit(
        Expense $expense,
        CategoryService $categoryService
    ): View {
        $categories = Cache::rememberForever('expenses.categories', fn (): Collection => $categoryService->all());

        return view('auth.expense.edit', ['expense' => $expense, 'categories' => $categories]);
    }

    public function update(
        UpdateExpenseRequest $updateExpenseRequest,
        Expense $expense,
        ExpenseService $expenseService
    ): RedirectResponse {
        $expenseData = ExpenseData::createFromRequest($updateExpenseRequest);

        $expenseService->update($expense, $expenseData);

        return to_route('expense.index', ['page' => $updateExpenseRequest->input('page')])->with('status', 'Expense updated successfully');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return to_route('expense.index')->with('status', 'Expense deleted successfully');
    }
}
