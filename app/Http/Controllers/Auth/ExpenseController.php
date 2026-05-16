<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Expense\StoreExpenseRequest;
use App\Http\Requests\Auth\Expense\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\Models\ExpenseService;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(ExpenseService $expenseService): View
    {
        $expenses = $expenseService->paginate();

        return view('auth.expense.index', compact('expenses'));
    }

    public function create(): View
    {
        return view('auth.expense.create');
    }

    public function store(StoreExpenseRequest $request)
    {
        //
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
