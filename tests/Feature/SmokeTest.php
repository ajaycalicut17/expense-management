<?php

use App\Models\Expense;
use App\Models\User;

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
