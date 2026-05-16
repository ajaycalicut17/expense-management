<?php

namespace App\Http\Controllers\Guest;

use App\Data\Models\UserData;
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
        StoreRegisterRequest $request,
        UserService $userService
    ): RedirectResponse {
        $data = UserData::createFromRequest($request);

        $userService->create($data);

        return to_route('index')->with('status', 'User registered successfully');
    }
}
