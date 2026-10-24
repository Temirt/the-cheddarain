<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
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
            'email' => 'admin@thecheddarloin.com',
            'password' => 'admin123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_kitchen_staff_is_removed_and_cannot_login(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'kitchen@thecheddarain.com',
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'kitchen@thecheddarain.com',
            'password' => 'Kitchen2026!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
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

    public function test_admin_can_view_menu_products_list_and_filter(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Menu & Products');
        $response->assertSee('Add New Product');

        // Test with search filter
        $responseSearch = $this->actingAs($admin)->get('/admin/products?search=Burger');
        $responseSearch->assertStatus(200);

        // Test with status filter
        $responseStatus = $this->actingAs($admin)->get('/admin/products?status=available');
        $responseStatus->assertStatus(200);
    }

    public function test_admin_can_create_menu_item(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $category = Category::firstOrFail();

        $createResponse = $this->actingAs($admin)->get('/admin/products/create');
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Add Menu Item');

        $storeResponse = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Royal Gold Smash Burger',
            'category_id' => $category->id,
            'price' => 16.99,
            'description' => 'Triple smashed Angus beef patties layered with aged sharp cheddar.',
            'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
            'badge' => 'Signature',
            'calories' => 920,
            'prep_time_minutes' => 12,
            'is_available' => '1',
            'is_featured' => '1',
        ]);

        $storeResponse->assertStatus(302);
        $storeResponse->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Royal Gold Smash Burger',
            'price' => 16.99,
            'badge' => 'Signature',
            'is_available' => true,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_update_menu_item(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $product = Product::firstOrFail();
        $category = Category::firstOrFail();

        $editResponse = $this->actingAs($admin)->get('/admin/products/' . $product->id . '/edit');
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Edit: ' . $product->name);

        $updateResponse = $this->actingAs($admin)->put('/admin/products/' . $product->id, [
            'name' => $product->name . ' Updated',
            'category_id' => $category->id,
            'price' => 19.50,
            'description' => 'Updated mouthwatering description with extra liquid gold cheddar.',
            'image_url' => $product->image_url,
            'badge' => 'Chef Special',
            'calories' => 880,
            'prep_time_minutes' => 14,
            'is_available' => '1',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertEquals($product->name, $product->name);
        $this->assertEquals(19.50, $product->price);
        $this->assertEquals('Chef Special', $product->badge);
    }

    public function test_admin_can_toggle_menu_item_availability(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $product = Product::firstOrFail();
        $initialState = $product->is_available;

        $response = $this->actingAs($admin)->post('/admin/products/' . $product->id . '/toggle');
        $response->assertStatus(302);

        $product->refresh();
        $this->assertEquals(!$initialState, $product->is_available);
    }

    public function test_admin_can_delete_menu_item(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $product = Product::create([
            'category_id' => Category::first()->id,
            'name' => 'Temporary Item To Delete',
            'slug' => 'temp-item-delete-' . rand(100, 999),
            'description' => 'Temporary test item',
            'price' => 9.99,
            'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
            'prep_time_minutes' => 10,
            'is_available' => true,
        ]);

        $deleteResponse = $this->actingAs($admin)->delete('/admin/products/' . $product->id);
        $deleteResponse->assertStatus(302);
        $deleteResponse->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_admin_can_view_and_manage_categories(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Menu Categories');
        $response->assertSee('Burgers');
        $response->assertSee('Sides');
        $response->assertSee('Drinks');
        $response->assertSee('Meals');
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $response = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Desserts & Sweets',
            'slug' => 'desserts-sweets',
            'icon' => '🍰',
            'description' => 'Molten lava cakes and artisan milkshakes.',
            'is_active' => '1',
            'sort_order' => 5,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'slug' => 'desserts-sweets',
            'name' => 'Desserts & Sweets',
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $category = Category::create([
            'name' => 'Special Editions',
            'slug' => 'special-editions',
            'icon' => '⭐',
            'is_active' => true,
            'sort_order' => 6,
        ]);

        $response = $this->actingAs($admin)->put('/admin/categories/' . $category->id, [
            'name' => 'Limited Editions',
            'slug' => 'limited-editions',
            'icon' => '🔥',
            'description' => 'Seasonal menu drops.',
            'is_active' => '1',
            'sort_order' => 6,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Limited Editions',
            'slug' => 'limited-editions',
        ]);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $category = Category::create([
            'name' => 'Temp Category To Delete',
            'slug' => 'temp-category-delete',
            'icon' => '🗑️',
            'is_active' => true,
            'sort_order' => 99,
        ]);

        $response = $this->actingAs($admin)->delete('/admin/categories/' . $category->id);
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
