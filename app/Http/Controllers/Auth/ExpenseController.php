<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

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

    public function store(Request $request)
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

    public function update(Request $request, Expense $expense)
    {
        //
    }

    public function destroy(Expense $expense)
    {
        //
    }
}
