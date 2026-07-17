<?php

use App\Models\Expense;
use App\Models\User;

it('may smoke test for guest routes', function () {
    $pages = visit([
        '/',
        '/register',
    ]);
 
    $pages->assertNoSmoke();
});

it('may smoke test for authenticated routes', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $expense = Expense::factory()->create([
        'user_id' => $user->id,
    ]);

    $pages = visit([
        '/dashboard',
        '/expense',
        '/expense/create',
        '/expense/' . $expense->id . '/edit?page=1',
    ]);
 
    $pages->assertNoSmoke();
});
