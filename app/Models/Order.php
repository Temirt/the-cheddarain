<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY_PICKUP = 'ready_for_pickup';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'fulfillment_type',
        'delivery_address',
        'delivery_apartment',
        'delivery_city',
        'delivery_notes',
        'special_instructions',
        'subtotal',
        'delivery_fee',
        'tax',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'estimated_prep_minutes',
        'estimated_delivery_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'estimated_prep_minutes' => 'integer',
        'estimated_delivery_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'asc');
    }

    /**
     * Map current status to McDonald's 5-step timeline index (1 to 5)
     */
    public function getTimelineStepAttribute(): int
    {
        return match ($this->status) {
            self::STATUS_PENDING => 1,
            self::STATUS_CONFIRMED => 2,
            self::STATUS_PREPARING => 3,
            self::STATUS_READY_PICKUP, self::STATUS_OUT_FOR_DELIVERY => 4,
            self::STATUS_DELIVERED => 5,
            default => 0, // cancelled
        };
    }

    /**
     * Human-readable label for the current status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Order Placed (Awaiting Confirmation)',
            self::STATUS_CONFIRMED => 'Order Confirmed',
            self::STATUS_PREPARING => 'Preparing Your Food',
            self::STATUS_READY_PICKUP => 'Ready for Pickup',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
            self::STATUS_DELIVERED => 'Delivered / Completed',
            self::STATUS_CANCELLED => 'Order Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Generate WhatsApp enquiry link for this order
     */
    public function getWhatsAppLinkAttribute(): string
    {
        $phone = RestaurantSetting::get('whatsapp_number', env('WHATSAPP_NUMBER', '15552433327'));
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $message = urlencode("Hello The Cheddarloin, I have an enquiry regarding my Order #{$this->order_reference}.");
        return "https://wa.me/{$phone}?text={$message}";
    }
}
