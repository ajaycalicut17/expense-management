<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View
    {
        return view('guest.login');
    }

    public function login()
    {
        // code...
    }

    public function logout()
    {
        // code...
    }
}
