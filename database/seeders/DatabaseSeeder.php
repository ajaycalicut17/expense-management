<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $users = User::factory(10)->create();

        $categories = Category::factory(10)->create();

        // Expense::factory(10)
        //     ->recycle([
        //         $users,
        //         $categories,
        //     ])
        //     ->create();
    }
}
