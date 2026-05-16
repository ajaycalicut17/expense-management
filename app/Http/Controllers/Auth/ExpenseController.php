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

    public function show(Expense $expense): View
    {
        return view('auth.expense.show');
    }

    public function edit(Expense $expense): View
    {
        return view('auth.expense.edit');
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        //
    }

    public function destroy(Expense $expense)
    {
        //
    }
}
