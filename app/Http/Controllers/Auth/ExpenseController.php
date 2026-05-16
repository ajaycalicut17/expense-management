<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Expense\StoreExpenseRequest;
use App\Http\Requests\Auth\Expense\UpdateExpenseRequest;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::paginate(2);

        return view('auth.expense.index', compact('expenses'));
    }

    public function create()
    {
        return view('auth.expense.create');
    }

    public function store(StoreExpenseRequest $request)
    {
        //
    }

    public function show(Expense $expense)
    {
        return view('auth.expense.show');
    }

    public function edit(Expense $expense)
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
