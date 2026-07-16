<?php

declare(strict_types=1);
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('redirects unauthenticated user to login', function (): void {
    $testResponse = $this->get('/expense');

    $testResponse->assertRedirect('/');
});

test('authenticated user can access expense page', function (): void {
    $user = User::factory()->create();
    $testResponse = $this->actingAs($user)->get('/expense');

    $testResponse->assertSuccessful();
});

test('index page shows paginated expenses', function (): void {
    $user = User::factory()->create();
    $testResponse = $this->actingAs($user)->get('/expense');

    $testResponse->assertSuccessful();
    $testResponse->assertViewHasAll([
        'expenses',
    ]);
    $testResponse->assertSeeTextInOrder([
        'Add',
        'Sl.No',
        'Category',
        'Amount',
        'Description',
        'Spent At',
        'Action',
    ]);
});

test('create page shows expense create form', function (): void {
    $user = User::factory()->create();
    $testResponse = $this->actingAs($user)->get('/expense/create');

    $testResponse->assertSuccessful();
    $testResponse->assertViewHasAll([
        'categories',
    ]);
});

test('store validation fails with invalid data', function (): void {
    $user = User::factory()->create();
    $testResponse = $this->actingAs($user)->post('/expense', [
        'category_id' => '',
        'amount' => '',
        'description' => '',
        'spent_at' => '',
    ]);

    $testResponse->assertInvalid([
        'category_id' => 'The category field is required.',
        'amount' => 'The amount field is required.',
        'description' => 'The description field is required.',
        'spent_at' => 'The spent at field is required.',
    ]);
});

test('store creates new expense', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $now = now();

    $testResponse = $this->actingAs($user)->post('/expense', [
        'category_id' => $category->id,
        'amount' => 100,
        'description' => 'Test expense',
        'spent_at' => $now->toString(),
    ]);

    $testResponse->assertRedirect('/expense');
    $testResponse->assertSessionHas('status', 'Expense added successfully');
    $this->assertDatabaseHas('expenses', [
        'user_id' => $user->id,
        'category_id' => $category->id,
        'amount' => 10000,
        'description' => 'Test expense',
        'spent_at' => $now->format('Y-m-d H:i:s'),
    ]);
});

test('show page shows expense details', function (): void {
    $user = User::factory()->create();
    $expense = Expense::factory()->create([
        'user_id' => $user->id,
    ]);

    $testResponse = $this->actingAs($user)->get('/expense/'.$expense->id);

    $testResponse->assertSuccessful();
    $testResponse->assertViewHasAll([
        'expense',
        'categories',
    ]);
});

test('edit page shows expense edit form', function (): void {
    $user = User::factory()->create();
    $expense = Expense::factory()->create([
        'user_id' => $user->id,
    ]);

    $testResponse = $this->actingAs($user)->get(sprintf('/expense/%s/edit', $expense->id));

    $testResponse->assertSuccessful();
    $testResponse->assertViewHasAll([
        'expense',
        'categories',
    ]);
});

test('update form validation', function (): void {
    $user = User::factory()->create();
    $expense = Expense::factory()->create([
        'user_id' => $user->id,
    ]);

    $testResponse = $this->actingAs($user)->put('/expense/'.$expense->id, [
        'category_id' => '',
        'amount' => '',
        'description' => '',
        'spent_at' => '',
    ]);

    $testResponse->assertInvalid([
        'category_id' => 'The category field is required.',
        'amount' => 'The amount field is required.',
        'description' => 'The description field is required.',
        'spent_at' => 'The spent at field is required.',
    ]);
});

test('update expense', function (): void {
    $user = User::factory()->create();
    $expense = Expense::factory()->create([
        'user_id' => $user->id,
    ]);
    $category = Category::factory()->create();
    $now = now();

    $testResponse = $this->actingAs($user)->put('/expense/'.$expense->id, [
        'category_id' => $category->id,
        'amount' => 100,
        'description' => 'Test expense',
        'spent_at' => $now->toString(),
    ]);

    $testResponse->assertRedirect('/expense');
    $testResponse->assertSessionHas('status', 'Expense updated successfully');
    $this->assertDatabaseHas('expenses', [
        'user_id' => $user->id,
        'category_id' => $category->id,
        'amount' => 10000,
        'description' => 'Test expense',
        'spent_at' => $now->format('Y-m-d H:i:s'),
    ]);
});

test('delete expense', function (): void {
    $user = User::factory()->create();
    $expense = Expense::factory()->create([
        'user_id' => $user->id,
    ]);

    $testResponse = $this->actingAs($user)->delete('/expense/'.$expense->id);

    $testResponse->assertRedirect('/expense');
    $testResponse->assertSessionHas('status', 'Expense deleted successfully');
    $this->assertSoftDeleted('expenses', [
        'id' => $expense->id,
    ]);
});

it('may create the expense', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    
    $this->actingAs($user);

    $visit = visit('/expense');
    $visit->click('Add')
        ->select('category_id', $category->id)
        ->fill('amount', '100')
        ->fill('description', 'Test description')
        ->fill('spent_at', '2026-07-16T14:30')
        ->click('Save')
        ->assertSee('Expense added successfully');
});

it('may update the expense', function (): void {
    $user = User::factory()->create();
    Expense::factory()
        ->recycle($user)
        ->create();
    
    $this->actingAs($user);

    $visit = visit('/expense');
    $visit->click('Edit')
        ->click('Save')
        ->assertSee('Expense updated successfully');
});

