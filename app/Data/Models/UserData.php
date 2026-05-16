<?php

namespace App\Data\Models;

use Illuminate\Http\Request;

class UserData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
    ) {}

    public static function createFromRequest(Request $request): self
    {
        return new self(
            id: $request->input('id'),
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }
}
