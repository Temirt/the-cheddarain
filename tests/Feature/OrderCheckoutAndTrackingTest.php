<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Tests\TestCase;

class OrderCheckoutAndTrackingTest extends TestCase
{
    public function test_customer_can_checkout_and_generate_unique_order_reference(): void
    {
        $product = Product::where('slug', 'the-ultimate-cheddar-lava')->firstOrFail();

        $payload = [
            'customer_name' => 'John Test Customer',
            'customer_email' => 'john.test@example.com',
            'customer_phone' => '555-891-2345',
            'fulfillment_type' => 'delivery',
            'delivery_address' => '404 Burger Lane',
            'delivery_city' => 'Springfield',
            'delivery_notes' => 'Ring bell',
            'payment_method' => 'cash_on_delivery',
            'cart_items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ],
        ];

        $response = $this->post('/checkout/process', $payload);

        $response->assertStatus(302);
        
        // Assert order exists in database
        $order = Order::where('customer_email', 'john.test@example.com')->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('CHED-', $order->order_reference);
        $this->assertEquals(Order::STATUS_PENDING, $order->status);
        $this->assertEquals(2, $order->items->sum('quantity'));

        // Assert audit log exists
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'to_status' => Order::STATUS_PENDING,
        ]);
    }

    public function test_tracking_requires_phone_verification_for_unauthenticated_sessions(): void
    {
        $order = Order::where('order_reference', 'CHED-782901')->firstOrFail();

        // Visiting directly without session or query phone
        $response = $this->get('/track/' . $order->order_reference);
        $response->assertStatus(200);
        $response->assertSee('Verify Your Order Access');

        // Visiting with incorrect phone number
        $responseWithWrongPhone = $this->get('/track/' . $order->order_reference . '?phone=1112223333');
        $responseWithWrongPhone->assertStatus(200);
        $responseWithWrongPhone->assertSee('The phone number provided does not match');

        // Visiting with correct phone number
        $responseWithCorrectPhone = $this->get('/track/' . $order->order_reference . '?phone=5551234567');
        $responseWithCorrectPhone->assertStatus(200);
        $responseWithCorrectPhone->assertSee('Live Order Tracking');
        $responseWithCorrectPhone->assertSee($order->customer_name);
    }

    public function test_api_tracking_endpoint_returns_accurate_timeline_data(): void
    {
        $order = Order::where('order_reference', 'CHED-782901')->firstOrFail();

        $response = $this->getJson('/api/orders/' . $order->order_reference . '/track');
        $response->assertStatus(200);
        $response->assertJson([
            'reference' => 'CHED-782901',
            'status' => 'preparing',
            'timeline_step' => 3,
        ]);
        $response->assertJsonStructure([
            'reference',
            'status',
            'status_label',
            'timeline_step',
            'progress_percent',
            'fulfillment_type',
            'status_histories',
        ]);
    }
}
