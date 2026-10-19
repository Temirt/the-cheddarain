<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    /**
     * Show tracking lookup form or redirect if reference provided
     */
    public function index(Request $request)
    {
        $reference = $request->query('ref');
        if ($reference) {
            return redirect()->route('order.track.show', ['reference' => strtoupper(trim($reference))]);
        }
        return view('pages.track-search');
    }

    /**
     * Display the McDonald's inspired live tracking page
     */
    public function show(Request $request, $reference)
    {
        $reference = strtoupper(trim($reference));
        $order = Order::with(['items', 'statusHistories.staff'])
            ->where('order_reference', $reference)
            ->first();

        if (!$order) {
            return redirect()->route('order.track')
                ->with('error', "No order found with reference #{$reference}. Please check and try again.");
        }

        // Check verification (Session or Phone Number)
        $isVerified = session('verified_order_' . $reference, false);
        $phoneInput = $request->query('phone') ?? $request->input('phone');

        if (!$isVerified && $phoneInput) {
            $cleanInput = preg_replace('/[^0-9]/', '', $phoneInput);
            $cleanStored = preg_replace('/[^0-9]/', '', $order->customer_phone);

            // Check if phone matches (allow last 4 digits or full number)
            if ($cleanInput === $cleanStored || (strlen($cleanInput) >= 4 && str_ends_with($cleanStored, $cleanInput))) {
                session(['verified_order_' . $reference => true]);
                $isVerified = true;
            } else {
                return view('pages.track', [
                    'order' => $order,
                    'isVerified' => false,
                    'error' => 'The phone number provided does not match the phone number registered for this order.',
                ]);
            }
        }

        $restaurantPhone = RestaurantSetting::get('restaurant_phone', '+1 (555) 243-3327');
        $whatsappNumber = RestaurantSetting::get('whatsapp_number', '15552433327');

        return view('pages.track', compact('order', 'isVerified', 'restaurantPhone', 'whatsappNumber'));
    }

    /**
     * Verify phone number via POST request
     */
    public function verifyPhone(Request $request, $reference)
    {
        $request->validate([
            'phone' => 'required|string|min:4',
        ]);

        return redirect()->route('order.track.show', [
            'reference' => $reference,
            'phone' => $request->phone,
        ]);
    }

    /**
     * REST API endpoint polled by Vanilla JS every 7 seconds
     * Protected by rate limiting in routes
     */
    public function apiTrack($reference)
    {
        $reference = strtoupper(trim($reference));
        $order = Order::with(['items', 'statusHistories.staff'])
            ->where('order_reference', $reference)
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Calculate progress percentage and current active timeline step
        $step = $order->timeline_step;
        $stepProgressMap = [
            1 => 15,  // Placed
            2 => 35,  // Confirmed
            3 => 65,  // Preparing
            4 => 85,  // Ready / Out for Delivery
            5 => 100, // Delivered / Complete
            0 => 0,   // Cancelled
        ];

        $percentage = $stepProgressMap[$step] ?? 0;

        return response()->json([
            'reference' => $order->order_reference,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'timeline_step' => $step,
            'progress_percent' => $percentage,
            'fulfillment_type' => $order->fulfillment_type,
            'estimated_prep_minutes' => $order->estimated_prep_minutes,
            'estimated_delivery_at' => $order->estimated_delivery_at ? $order->estimated_delivery_at->toIso8601String() : null,
            'last_updated' => $order->updated_at->diffForHumans(),
            'updated_at_formatted' => $order->updated_at->format('h:i A - M d, Y'),
            'cancellation_reason' => $order->cancellation_reason,
            'items_count' => $order->items->sum('quantity'),
            'total' => (float)$order->total,
            'status_histories' => $order->statusHistories->map(function ($h) {
                return [
                    'to_status' => $h->to_status,
                    'note' => $h->note,
                    'time' => $h->created_at->format('h:i A'),
                    'staff_name' => $h->staff ? $h->staff->name : null,
                ];
            }),
        ]);
    }
}
