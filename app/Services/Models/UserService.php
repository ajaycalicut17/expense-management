<?php

namespace App\Services\Models;

use App\Data\Models\UserData;
use App\Models\User;

class UserService
{
    public function create(UserData $data): User
    {
        $user = new User;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->save();

        return $user;
    }
}
