<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@thecheddarain.com',
            'password' => 'admin123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_can_transition_order_status_and_records_audit_trail(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $order = Order::where('order_reference', 'CHED-782902')->firstOrFail();

        $response = $this->actingAs($admin)->post('/admin/orders/' . $order->id . '/status', [
            'status' => 'delivered',
            'note' => 'Customer picked up at counter. Payment confirmed.',
        ]);

        $response->assertStatus(302);

        $order->refresh();
        $this->assertEquals('delivered', $order->status);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'to_status' => 'delivered',
            'user_id' => $admin->id,
            'note' => 'Customer picked up at counter. Payment confirmed.',
        ]);
    }
}
