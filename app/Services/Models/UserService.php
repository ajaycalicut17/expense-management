<?php

declare(strict_types=1);

namespace App\Services\Models;

use App\Data\Models\UserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function create(UserData $userData): User
    {
        $user = new User;
        $user->role = $userData->role;
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->password = $userData->password;
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
