<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    public function test_customer_can_view_register_form(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Create Your Account');
    }

    public function test_customer_can_register_successfully(): void
    {
        $payload = [
            'name' => 'Alice Customer',
            'email' => 'alice.new@example.com',
            'phone' => '555-987-6543',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ];

        $response = $this->post('/register', $payload);
        $response->assertStatus(302);
        $response->assertRedirect(route('account'));

        $this->assertDatabaseHas('users', [
            'email' => 'alice.new@example.com',
            'role' => 'customer',
        ]);

        $this->assertAuthenticated();
    }

    public function test_customer_can_view_login_form(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Customer Sign In');
    }

    public function test_customer_can_login_and_access_account(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => 'TestSecret123!',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'TestSecret123!',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));
        $this->assertAuthenticatedAs($user);

        // Access account portal
        $accountResponse = $this->actingAs($user)->get('/account');
        $accountResponse->assertStatus(200);
        $accountResponse->assertSee($user->name);
        $accountResponse->assertSee('My Orders');
    }

    public function test_customer_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->post('/logout');
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
