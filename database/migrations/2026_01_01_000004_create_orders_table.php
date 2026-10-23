<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_reference')->unique(); // e.g. CHED-782914
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->enum('fulfillment_type', ['pickup', 'delivery'])->default('delivery');
            $table->string('delivery_address')->nullable();
            $table->string('delivery_apartment')->nullable();
            $table->string('delivery_city')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->text('special_instructions')->nullable();
            
            // Financial calculations (server-validated)
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->decimal('tax', 8, 2)->default(0.00);
            $table->decimal('total', 10, 2);

            // Order Tracking Status (McDonald's inspired 5-step lifecycle + edges)
            // 1. pending (awaiting confirmation)
            // 2. confirmed (restaurant confirmed order)
            // 3. preparing (kitchen is making food)
            // 4. ready_for_pickup / out_for_delivery (ready or on vehicle)
            // 5. delivered / completed (done)
            // edge: cancelled
            $table->string('status')->default('pending');
            $table->string('payment_method')->default('cash_on_delivery'); // cash_on_delivery, payment_on_pickup, online_card
            $table->string('payment_status')->default('pending'); // pending, paid, failed

            $table->integer('estimated_prep_minutes')->nullable();
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
