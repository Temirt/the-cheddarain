<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $deliveryFee = (float)RestaurantSetting::get('delivery_fee', 3.99);
        $taxRate = (float)RestaurantSetting::get('tax_rate_percent', 8.5);
        $currency = RestaurantSetting::get('currency_symbol', '$');

        return view('pages.checkout', compact('deliveryFee', 'taxRate', 'currency'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'required|string|min:7|max:20',
            'fulfillment_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:fulfillment_type,delivery|nullable|string|max:255',
            'delivery_apartment' => 'nullable|string|max:100',
            'delivery_city' => 'required_if:fulfillment_type,delivery|nullable|string|max:100',
            'delivery_notes' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery,payment_on_pickup,online_card',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1|max:50',
            'cart_items.*.options' => 'nullable|array',
        ]);

        // Clean phone number (strip spaces/dashes for reliable verification)
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);

        return DB::transaction(function () use ($validated, $cleanPhone) {
            $subtotal = 0.00;
            $orderItemsData = [];

            // Re-calculate all prices strictly on the server
            foreach ($validated['cart_items'] as $itemInput) {
                $product = Product::findOrFail($itemInput['product_id']);
                
                if (!$product->is_available) {
                    return back()->withErrors(['cart' => "Sorry, {$product->name} is currently out of stock."]);
                }

                $itemBasePrice = (float)$product->price;
                $optionsPrice = 0.00;
                $selectedOptions = [];

                if (!empty($itemInput['options'])) {
                    foreach ($itemInput['options'] as $opt) {
                        // Validate option value price from database
                        if (isset($opt['value_id'])) {
                            $optionVal = ProductOptionValue::find($opt['value_id']);
                            if ($optionVal) {
                                $optionsPrice += (float)$optionVal->price;
                                $selectedOptions[] = [
                                    'option' => $opt['option_name'] ?? 'Extra',
                                    'choice' => $optionVal->name,
                                    'price' => (float)$optionVal->price,
                                ];
                            }
                        }
                    }
                }

                $unitPrice = $itemBasePrice + $optionsPrice;
                $itemSubtotal = $unitPrice * $itemInput['quantity'];
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $itemInput['quantity'],
                    'subtotal' => $itemSubtotal,
                    'selected_options' => $selectedOptions,
                ];
            }

            // Calculations
            $deliveryFee = ($validated['fulfillment_type'] === 'delivery') 
                ? (float)RestaurantSetting::get('delivery_fee', 3.99) 
                : 0.00;

            $taxRatePercent = (float)RestaurantSetting::get('tax_rate_percent', 8.5);
            $tax = round($subtotal * ($taxRatePercent / 100), 2);
            $total = round($subtotal + $deliveryFee + $tax, 2);

            // Generate unique Order Reference (e.g. CHED-782914)
            do {
                $reference = 'CHED-' . strtoupper(Str::random(6));
            } while (Order::where('order_reference', $reference)->exists());

            // Create Order
            $order = Order::create([
                'order_reference' => $reference,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $cleanPhone,
                'fulfillment_type' => $validated['fulfillment_type'],
                'delivery_address' => $validated['delivery_address'] ?? null,
                'delivery_apartment' => $validated['delivery_apartment'] ?? null,
                'delivery_city' => $validated['delivery_city'] ?? null,
                'delivery_notes' => $validated['delivery_notes'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax' => $tax,
                'total' => $total,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $validated['payment_method'],
                'payment_status' => ($validated['payment_method'] === 'online_card') ? 'paid' : 'pending',
                'estimated_prep_minutes' => 15,
            ]);

            // Save order items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Create initial status history audit log
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => Order::STATUS_PENDING,
                'note' => 'Order placed successfully by customer via website checkout.',
            ]);

            // Store in session so user is verified automatically for this order
            session([
                'verified_order_' . $reference => true,
                'last_order_reference' => $reference,
                'customer_phone' => $cleanPhone,
            ]);

            return redirect()->route('order.confirmation', ['reference' => $reference])
                ->with('clear_cart', true);
        });
    }

    public function confirmation($reference)
    {
        $order = Order::with('items')->where('order_reference', $reference)->firstOrFail();
        return view('pages.confirmation', compact('order'));
    }
}
