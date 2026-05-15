<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        return view('guest.register');
    }

    public function store()
    {
        //
    }
}
