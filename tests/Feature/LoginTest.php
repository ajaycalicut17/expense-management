<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_login_page_loads(): void
    {
        $testResponse = $this->get('/');

        $testResponse->assertSuccessful();
    }

    public function test_login_form_validation(): void
    {
        $testResponse = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $testResponse->assertInvalid([
            'email',
            'password',
        ]);
    }

    public function test_login_form_validation_with_invalid_email_and_password(): void
    {
        $testResponse = $this->post('/login', [
            'email' => 'invalid-email@example.com',
            'password' => 'invalid-password',
        ]);

        $testResponse->assertInvalid([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function test_login_form_with_valid_data(): void
    {
        $user = User::factory()->create();

        $testResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $testResponse->assertRedirect('/dashboard');
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $testResponse = $this->get('/dashboard');

        $testResponse->assertRedirect('/');
    }

    public function test_guest_middleware_redirects_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertRedirect('/dashboard');

        $response = $this->get('/register');

        $response->assertRedirect('/dashboard');
    }

    public function test_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $response = $this->get('/dashboard');

        $response->assertRedirect('/');
    }
}
