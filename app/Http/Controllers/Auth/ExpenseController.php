<?php

namespace App\Http\Controllers\Auth;

use App\Data\Models\ExpenseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Expense\StoreExpenseRequest;
use App\Http\Requests\Auth\Expense\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\Models\CategoryService;
use App\Services\Models\ExpenseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(ExpenseService $expenseService): View
    {
        $expenses = $expenseService->paginate();

        return view('auth.expense.index', compact('expenses'));
    }

    public function create(CategoryService $categoryService): View
    {
        $categories = $categoryService->all();

        return view('auth.expense.create', compact('categories'));
    }

    public function store(
        StoreExpenseRequest $request,
        ExpenseService $expenseService
    ): RedirectResponse {
        $data = ExpenseData::createFromRequest($request);
        $data->userId = $request->user()->id;

        $expenseService->create($data);

        return to_route('expense.index')->with('status', 'Expense added successfully');
    }

    public function show(
        Expense $expense,
        CategoryService $categoryService
    ): View {
        $categories = $categoryService->all();

        return view('auth.expense.show', compact('expense', 'categories'));
    }

    public function edit(
        Expense $expense,
        CategoryService $categoryService
    ): View {
        $categories = $categoryService->all();

        return view('auth.expense.edit', compact('expense', 'categories'));
    }

    public function update(
        UpdateExpenseRequest $request,
        Expense $expense,
        ExpenseService $expenseService
    ): RedirectResponse {
        $data = ExpenseData::createFromRequest($request);

        $expenseService->update($expense, $data);

        return to_route('expense.index', ['page' => $request->input('page')])->with('status', 'Expense updated successfully');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return to_route('expense.index')->with('status', 'Expense deleted successfully');
    }
}
