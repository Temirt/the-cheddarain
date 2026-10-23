<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->orderBy('created_at', 'desc');

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Search by reference or customer name / phone
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('order_reference', 'like', "%{$term}%")
                  ->orWhere('customer_name', 'like', "%{$term}%")
                  ->orWhere('customer_phone', 'like', "%{$term}%")
                  ->orWhere('customer_email', 'like', "%{$term}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $statuses = [
            'all' => 'All Orders',
            Order::STATUS_PENDING => 'Pending Confirmation',
            Order::STATUS_CONFIRMED => 'Confirmed',
            Order::STATUS_PREPARING => 'Preparing',
            Order::STATUS_READY_PICKUP => 'Ready for Pickup',
            Order::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
            Order::STATUS_DELIVERED => 'Delivered',
            Order::STATUS_CANCELLED => 'Cancelled / Rejected',
        ];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'statusHistories.staff'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status with mandatory audit log recording staff user & timestamp
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready_for_pickup,out_for_delivery,delivered,cancelled',
            'estimated_prep_minutes' => 'nullable|integer|min:1|max:180',
            'note' => 'nullable|string|max:500',
            'cancellation_reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $updateData = ['status' => $newStatus];

        if (isset($validated['estimated_prep_minutes'])) {
            $updateData['estimated_prep_minutes'] = $validated['estimated_prep_minutes'];
            $updateData['estimated_delivery_at'] = Carbon::now()->addMinutes($validated['estimated_prep_minutes'] + 15);
        }

        if ($newStatus === Order::STATUS_CANCELLED) {
            $updateData['cancellation_reason'] = $validated['cancellation_reason'];
        }

        if ($newStatus === Order::STATUS_DELIVERED) {
            $updateData['payment_status'] = 'paid';
        }

        $order->update($updateData);

        // Record in audit log history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'note' => $validated['note'] ?? "Status updated from '{$oldStatus}' to '{$newStatus}' by " . Auth::user()->name,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', "Order #{$order->order_reference} status successfully updated to '{$order->status_label}'.");
    }
}
