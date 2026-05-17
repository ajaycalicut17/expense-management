<?php

namespace Tests\Feature;

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
}
