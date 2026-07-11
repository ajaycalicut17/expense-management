<?php

declare(strict_types=1);
use App\Models\User;
uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

test('login page loads', function () {
    $testResponse = $this->get('/');

    $testResponse->assertSuccessful();
});
test('login form validation', function () {
    $testResponse = $this->post('/login', [
        'email' => '',
        'password' => '',
    ]);

    $testResponse->assertInvalid([
        'email',
        'password',
    ]);
});
test('login form validation with invalid email and password', function () {
    $testResponse = $this->post('/login', [
        'email' => 'invalid-email@example.com',
        'password' => 'invalid-password',
    ]);

    $testResponse->assertInvalid([
        'email' => 'The provided credentials do not match our records.',
    ]);
});
test('login form with valid data', function () {
    $user = User::factory()->create();

    $testResponse = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $testResponse->assertRedirect('/dashboard');
});
test('unauthenticated user cannot access dashboard', function () {
    $testResponse = $this->get('/dashboard');

    $testResponse->assertRedirect('/');
});
test('guest middleware redirects authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/');

    $response->assertRedirect('/dashboard');

    $response = $this->get('/register');

    $response->assertRedirect('/dashboard');
});
test('logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertRedirect('/');

    $response = $this->get('/dashboard');

    $response->assertRedirect('/');
});
