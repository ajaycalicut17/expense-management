<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: int
{
    case ADMIN = 1;
    case USER = 2;
}
