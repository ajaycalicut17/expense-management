<?php

namespace Tests\Feature;

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
}
