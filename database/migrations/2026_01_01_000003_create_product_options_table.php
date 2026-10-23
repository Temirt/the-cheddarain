<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('name'); // e.g. "Bun Choice", "Cheese Drizzle Level", "Extra Toppings"
            $table->string('type')->default('radio'); // radio, checkbox
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_option_id')->constrained('product_options')->onDelete('cascade');
            $table->string('name'); // e.g. "Brioche Bun", "Extra Cheddar Melt", "Double Smoked Bacon"
            $table->decimal('price', 8, 2)->default(0.00);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_values');
        Schema::dropIfExists('product_options');
    }
};
