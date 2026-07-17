<?php

declare(strict_types=1);
use App\Models\Expense;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Number;

uses(LazilyRefreshDatabase::class);

it('may smoke test for guest routes', function (): void {
    $arrayablePendingAwaitablePage = visit([
        '/',
        '/register',
    ]);

    $arrayablePendingAwaitablePage->assertNoSmoke();
});

it('may smoke test for authenticated routes', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $expense = Expense::factory()->create([
        'user_id' => $user->id,
    ]);

    $arrayablePendingAwaitablePage = visit([
        '/dashboard',
        '/expense',
        '/expense/create',
        '/expense/'.$expense->id.'/edit?page=1',
    ]);

    $arrayablePendingAwaitablePage->assertNoSmoke();
});
