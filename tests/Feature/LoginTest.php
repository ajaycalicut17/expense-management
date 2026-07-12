<?php

declare(strict_types=1);
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('login page loads', function (): void {
    $testResponse = $this->get('/');

    $testResponse->assertSuccessful();
});

test('login form validation', function (): void {
    $testResponse = $this->post('/login', [
        'email' => '',
        'password' => '',
    ]);

    $testResponse->assertInvalid([
        'email',
        'password',
    ]);
});

test('login form validation with invalid email and password', function (): void {
    $testResponse = $this->post('/login', [
        'email' => 'invalid-email@example.com',
        'password' => 'invalid-password',
    ]);

    $testResponse->assertInvalid([
        'email' => 'The provided credentials do not match our records.',
    ]);
});

test('login form with valid data', function (): void {
    $user = User::factory()->create();

    $testResponse = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $testResponse->assertRedirect('/dashboard');
});

test('unauthenticated user cannot access dashboard', function (): void {
    $testResponse = $this->get('/dashboard');

    $testResponse->assertRedirect('/');
});

test('guest middleware redirects authenticated user', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/');

    $response->assertRedirect('/dashboard');

    $response = $this->get('/register');

    $response->assertRedirect('/dashboard');
});

test('logout', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertRedirect('/');

    $response = $this->get('/dashboard');

    $response->assertRedirect('/');
});

it('may login the user', function (): void {
    $user = User::factory()->create();

    $pendingAwaitablePage = visit('/');

    $pendingAwaitablePage->fill('email', $user->email)
        ->fill('password', 'password')
        ->click('Log in')
        ->assertSee('Dashboard')
        ->assertSee($user->name);

    $this->assertAuthenticated();
});
