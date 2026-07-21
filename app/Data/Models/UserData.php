<?php

declare(strict_types=1);

namespace App\Data\Models;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;

class UserData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?int $id = null,
        public ?RoleEnum $role = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
    ) {}

    public static function createFromRequest(Request $request): self
    {
        return new self(
            id: $request->integer('id'),
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        );
    }
}
