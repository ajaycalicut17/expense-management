<?php

declare(strict_types=1);
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('register page loads', function (): void {
    $testResponse = $this->get('/register');

    $testResponse->assertStatus(200);
});
test('register form validation', function (): void {
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
});
test('register form validation with valid data', function (): void {
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
});
