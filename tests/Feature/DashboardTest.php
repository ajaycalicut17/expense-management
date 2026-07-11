<?php

declare(strict_types=1);
use App\Enums\RoleEnum;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Number;

uses(LazilyRefreshDatabase::class);

test('dashboard returns 200', function (): void {
    $user = User::factory()->create();
    $testResponse = $this->actingAs($user)->get('/dashboard');

    $testResponse->assertStatus(200);
});

test('average daily expense response', function (): void {
    $now = now();
    $user = User::factory()->create();
    $testResponse = $this->actingAs($user)->get('/average-daily-expense?month='.$now->month.'&year='.$now->year);

    $testResponse->assertJsonStructure([
        'data' => [
            'average_daily_expenses',
        ],
    ]);
});

test('average daily expense calculation', function (): void {
    $user = User::factory()->create();
    $now = now();
    Expense::factory()->count(10)->create([
        'user_id' => $user->id,
        'amount' => 100,
        'spent_at' => $now,
    ]);
    $testResponse = $this->actingAs($user)->get('/average-daily-expense?month='.$now->month.'&year='.$now->year);

    $testResponse->assertJson([
        'data' => [
            'average_daily_expenses' => Number::currency(100),
        ],
    ]);
});

test('total expenses by category response', function (): void {
    $now = now();
    $user = User::factory()->create();
    $testResponse = $this->actingAs($user)->get('/total-expenses-by-category?month='.$now->month.'&year='.$now->year);

    $testResponse->assertJsonStructure([
        'labels',
        'data',
    ]);
});

test('total expenses by category calculation', function (): void {
    $now = now();
    $user = User::factory()->create([
        'role' => RoleEnum::USER,
    ]);
    $category = Category::factory()->create();
    Expense::factory()->count(10)->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'amount' => 100,
        'spent_at' => $now,
    ]);
    $testResponse = $this->actingAs($user)->get('/total-expenses-by-category?user_id='.$user->id.'&month='.$now->month.'&year='.$now->year);

    $testResponse->assertJson([
        'labels' => [
            $category->name,
        ],
        'data' => [
            1000,
        ],
    ]);
});

test('total expenses by category with respect to user', function (): void {
    $now = now();
    $category = Category::factory()->create();

    $user = User::factory()->create([
        'role' => RoleEnum::USER,
    ]);
    Expense::factory()->count(10)->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'amount' => 100,
        'spent_at' => $now,
    ]);

    $user1 = User::factory()->create([
        'role' => RoleEnum::USER,
    ]);
    Expense::factory()->count(10)->create([
        'user_id' => $user1->id,
        'category_id' => $category->id,
        'amount' => 50,
        'spent_at' => $now,
    ]);

    $response = $this->actingAs($user)->get('/total-expenses-by-category?month='.$now->month.'&year='.$now->year);
    $response->assertJson([
        'labels' => [
            $category->name,
        ],
        'data' => [
            1000,
        ],
    ]);

    $response = $this->actingAs($user1)->get('/total-expenses-by-category?month='.$now->month.'&year='.$now->year);
    $response->assertJson([
        'labels' => [
            $category->name,
        ],
        'data' => [
            500,
        ],
    ]);
});

test('total expenses by category with respect to user role', function (): void {
    $now = now();
    $category = Category::factory()->create();

    $admin = User::factory()->create([
        'role' => RoleEnum::ADMIN,
    ]);
    Expense::factory()->count(10)->create([
        'user_id' => $admin->id,
        'category_id' => $category->id,
        'amount' => 100,
        'spent_at' => $now,
    ]);

    $user = User::factory()->create([
        'role' => RoleEnum::USER,
    ]);
    Expense::factory()->count(10)->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'amount' => 50,
        'spent_at' => $now,
    ]);

    $response = $this->actingAs($admin)->get('/total-expenses-by-category?month='.$now->month.'&year='.$now->year);
    $response->assertJson([
        'labels' => [
            $category->name,
        ],
        'data' => [
            1500,
        ],
    ]);

    $response = $this->actingAs($user)->get('/total-expenses-by-category?month='.$now->month.'&year='.$now->year);
    $response->assertJson([
        'labels' => [
            $category->name,
        ],
        'data' => [
            500,
        ],
    ]);
});

test('only admin can see all users', function (): void {
    $admin = User::factory()->create([
        'role' => RoleEnum::ADMIN,
    ]);
    $response = $this->actingAs($admin)->get('/dashboard');
    $response->assertViewHas('users');

    $user = User::factory()->create([
        'role' => RoleEnum::USER,
    ]);
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertViewMissing('users');
});
