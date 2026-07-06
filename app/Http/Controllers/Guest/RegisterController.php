<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Data\Models\UserData;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\StoreRegisterRequest;
use App\Services\Models\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View
    {
        return view('guest.register');
    }

    public function store(
        StoreRegisterRequest $storeRegisterRequest,
        UserService $userService
    ): RedirectResponse {
        $userData = UserData::createFromRequest($storeRegisterRequest);
        $userData->role = RoleEnum::USER;

        $userService->create($userData);

        return to_route('index')->with('status', 'User registered successfully');
    }
}
