<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_register_page_loads(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_register_form_validation(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertInvalid([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
    }

    public function test_register_form_validation_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertValid();
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $response->assertRedirectToRoute('index');
        $response->assertSessionHas('status', 'User registered successfully');
    }
}
