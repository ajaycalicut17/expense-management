<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'role' => RoleEnum::ADMIN,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        // $users = User::factory(10)->create();

        $categories = array_map(function ($name) {
            $category = new Category;
            $category->name = $name;
            $category->save();

            return $category;
        }, CategoryFactory::CATEGORIES);

        // Expense::factory(10)
        //     ->recycle([
        //         $users,
        //         $categories,
        //     ])
        //     ->create();
    }
}
