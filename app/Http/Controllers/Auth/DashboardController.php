<?php

namespace App\Http\Controllers\Auth;

use App\Data\Filter\DateData;
use App\Data\Models\ExpenseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Dashboard\AverageDailyExpenseRequest;
use App\Http\Resources\Auth\AverageDailyExpenseResource;
use App\Services\Models\ExpenseService;
use App\Services\Models\UserService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(UserService $userService): View
    {
        $users = $userService->all();

        return view('auth.dashboard.index', compact('users'));
    }

    public function averageDailyExpense(
        AverageDailyExpenseRequest $request,
        ExpenseService $expenseService
    ) {
        $dateData = DateData::createFromRequest($request);
        $expenseData = new ExpenseData(
            userId: $request->user()->id,
        );
        $averageDailyExpense = $expenseService->averageDailyExpense(
            $expenseData,
            $dateData
        );

        return AverageDailyExpenseResource::make($averageDailyExpense);
    }
}
