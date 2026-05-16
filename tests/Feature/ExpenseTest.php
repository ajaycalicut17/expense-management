<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_redirects_unauthenticated_user_to_login()
    {
        $response = $this->get('/expense');

        $response->assertRedirect('/');
    }

    public function test_authenticated_user_can_access_expense_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/expense');

        $response->assertSuccessful();
    }

    public function test_index_page_shows_paginated_expenses()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/expense');

        $response->assertSuccessful();
        $response->assertViewHasAll([
            'expenses',
        ]);
        $response->assertSeeTextInOrder([
            'Add',
            'Sl.No',
            'User',
            'Category',
            'Amount',
            'Description',
            'Spent At',
            'Action',
        ]);
    }

    public function test_create_page_shows_expense_create_form()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/expense/create');

        $response->assertSuccessful();
        $response->assertViewHasAll([
            'categories',
        ]);
    }

    public function test_store_validation_fails_with_invalid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/expense', [
            'category_id' => '',
            'amount' => '',
            'description' => '',
            'spent_at' => '',
        ]);

        $response->assertInvalid([
            'category_id' => 'The category field is required.',
            'amount' => 'The amount field is required.',
            'description' => 'The description field is required.',
            'spent_at' => 'The spent at field is required.',
        ]);
    }

    public function test_store_creates_new_expense()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $now = now();

        $response = $this->actingAs($user)->post('/expense', [
            'category_id' => $category->id,
            'amount' => 100,
            'description' => 'Test expense',
            'spent_at' => $now,
        ]);

        $response->assertRedirect('/expense');
        $response->assertSessionHas('status', 'Expense added successfully');
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10000,
            'description' => 'Test expense',
            'spent_at' => $now->format('Y-m-d H:i:s'),
        ]);
    }

    public function test_show_page_shows_expense_details()
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("/expense/{$expense->id}");

        $response->assertSuccessful();
        $response->assertViewHasAll([
            'expense',
            'categories',
        ]);
    }

    public function test_edit_page_shows_expense_edit_form()
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("/expense/{$expense->id}/edit");

        $response->assertSuccessful();
        $response->assertViewHasAll([
            'expense',
            'categories',
        ]);
    }

    public function test_update_form_validation()
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put("/expense/{$expense->id}", [
            'category_id' => '',
            'amount' => '',
            'description' => '',
            'spent_at' => '',
        ]);

        $response->assertInvalid([
            'category_id' => 'The category field is required.',
            'amount' => 'The amount field is required.',
            'description' => 'The description field is required.',
            'spent_at' => 'The spent at field is required.',
        ]);
    }

    public function test_update_expense()
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);
        $category = Category::factory()->create();
        $now = now();

        $response = $this->actingAs($user)->put('/expense/'.$expense->id, [
            'category_id' => $category->id,
            'amount' => 100,
            'description' => 'Test expense',
            'spent_at' => $now,
        ]);

        $response->assertRedirect('/expense');
        $response->assertSessionHas('status', 'Expense updated successfully');
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10000,
            'description' => 'Test expense',
            'spent_at' => $now->format('Y-m-d H:i:s'),
        ]);
    }
}
