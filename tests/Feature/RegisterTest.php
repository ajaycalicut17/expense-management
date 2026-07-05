<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class RegisterTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_register_page_loads(): void
    {
        $testResponse = $this->get('/register');

        $testResponse->assertStatus(200);
    }

    public function test_register_form_validation(): void
    {
        $testResponse = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $testResponse->assertInvalid([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
    }

    public function test_register_form_validation_with_valid_data(): void
    {
        $testResponse = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $testResponse->assertValid();
        $this->assertDatabaseHas('users', [
            'role' => RoleEnum::USER,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $testResponse->assertRedirectToRoute('index');
        $testResponse->assertSessionHas('status', 'User registered successfully');
    }
}
