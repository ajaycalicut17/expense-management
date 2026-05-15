<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index()
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
