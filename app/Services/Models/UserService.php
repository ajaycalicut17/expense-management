<?php

namespace App\Services\Models;

use App\Data\Models\UserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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

    public function all(): Collection
    {
        return User::query()
            ->select([
                'id',
                'name',
            ])
            ->get();
    }
}
