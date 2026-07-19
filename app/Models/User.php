<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\RoleEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property RoleEnum $role
 * @property bool $isAdmin
 * @property bool $isUser
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => RoleEnum::class,
        ];
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): bool => $attributes['role'] === RoleEnum::ADMIN->value,
        );
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isUser(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): bool => $attributes['role'] === RoleEnum::USER->value,
        );
    }
}
