<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class ExpenseTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_redirects_unauthenticated_user_to_login(): void
    {
        $testResponse = $this->get('/expense');

        $testResponse->assertRedirect('/');
    }

    public function test_authenticated_user_can_access_expense_page(): void
    {
        $user = User::factory()->create();
        $testResponse = $this->actingAs($user)->get('/expense');

        $testResponse->assertSuccessful();
    }

    public function test_index_page_shows_paginated_expenses(): void
    {
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
    }

    public function test_create_page_shows_expense_create_form(): void
    {
        $user = User::factory()->create();
        $testResponse = $this->actingAs($user)->get('/expense/create');

        $testResponse->assertSuccessful();
        $testResponse->assertViewHasAll([
            'categories',
        ]);
    }

    public function test_store_validation_fails_with_invalid_data(): void
    {
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
    }

    public function test_store_creates_new_expense(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $now = now();

        $testResponse = $this->actingAs($user)->post('/expense', [
            'category_id' => $category->id,
            'amount' => 100,
            'description' => 'Test expense',
            'spent_at' => $now,
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
    }

    public function test_show_page_shows_expense_details(): void
    {
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
    }

    public function test_edit_page_shows_expense_edit_form(): void
    {
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
    }

    public function test_update_form_validation(): void
    {
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
    }

    public function test_update_expense(): void
    {
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
            'spent_at' => $now,
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
    }

    public function test_delete_expense(): void
    {
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
    }
}
