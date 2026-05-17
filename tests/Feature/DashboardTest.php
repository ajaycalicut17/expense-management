<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Number;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_dashboard_returns_200()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_average_daily_expense_response()
    {
        $now = now();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/average-daily-expense?month='.$now->month.'&year='.$now->year);

        $response->assertJsonStructure([
            'data' => [
                'average_daily_expenses',
            ],
        ]);
    }

    public function test_average_daily_expense_calculation()
    {
        $user = User::factory()->create();
        $now = now();
        Expense::factory()->count(10)->create([
            'user_id' => $user->id,
            'amount' => 100,
            'spent_at' => $now,
        ]);
        $response = $this->actingAs($user)->get('/average-daily-expense?month='.$now->month.'&year='.$now->year);

        $response->assertJson([
            'data' => [
                'average_daily_expenses' => Number::currency(100),
            ],
        ]);
    }

    public function test_total_expenses_by_category_response()
    {
        $now = now();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/total-expenses-by-category?month='.$now->month.'&year='.$now->year);

        $response->assertJsonStructure([
            'labels',
            'data',
        ]);
    }

    public function test_total_expenses_by_category_calculation()
    {
        $now = now();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        Expense::factory()->count(10)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100,
            'spent_at' => $now,
        ]);
        $response = $this->actingAs($user)->get('/total-expenses-by-category?user_id='.$user->id.'&month='.$now->month.'&year='.$now->year);

        $response->assertJson([
            'labels' => [
                $category->name,
            ],
            'data' => [
                1000,
            ],
        ]);
    }
}
