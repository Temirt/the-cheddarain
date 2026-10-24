<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use Illuminate\Database\Seeder;

class ProductOptionSeeder extends Seeder
{
    public function run(): void
    {
        $burgers = Product::whereHas('category', function ($q) {
            $q->whereIn('slug', ['burgers', 'meals']);
        })->get();

        foreach ($burgers as $burger) {
            // Bun Option (Radio, Required)
            $bunOption = ProductOption::create([
                'product_id' => $burger->id,
                'name' => 'Select Bun Style',
                'type' => 'radio',
                'is_required' => true,
            ]);

            ProductOptionValue::create(['product_option_id' => $bunOption->id, 'name' => 'Artisanal Brioche Bun', 'price' => 0.00, 'is_default' => true]);
            ProductOptionValue::create(['product_option_id' => $bunOption->id, 'name' => 'Toasted Sesame Seed Bun', 'price' => 0.00, 'is_default' => false]);
            ProductOptionValue::create(['product_option_id' => $bunOption->id, 'name' => 'Gluten-Free Artisan Bun', 'price' => 1.50, 'is_default' => false]);
            ProductOptionValue::create(['product_option_id' => $bunOption->id, 'name' => 'Crisp Lettuce Wrap (Low Carb)', 'price' => 0.00, 'is_default' => false]);

            // Cheese & Cheddar Upgrade (Checkbox)
            $cheeseOption = ProductOption::create([
                'product_id' => $burger->id,
                'name' => 'Cheese Drizzle & Upgrades',
                'type' => 'checkbox',
                'is_required' => false,
            ]);

            ProductOptionValue::create(['product_option_id' => $cheeseOption->id, 'name' => 'Extra Liquid Cheddar Drizzle', 'price' => 1.99, 'is_default' => false]);
            ProductOptionValue::create(['product_option_id' => $cheeseOption->id, 'name' => 'Aged Smoked Wisconsin Cheddar', 'price' => 1.49, 'is_default' => false]);
            ProductOptionValue::create(['product_option_id' => $cheeseOption->id, 'name' => 'Molten Pepper Jack Melt', 'price' => 1.25, 'is_default' => false]);

            // Extras & Add-ons (Checkbox)
            $extraOption = ProductOption::create([
                'product_id' => $burger->id,
                'name' => 'Extra Gourmet Toppings',
                'type' => 'checkbox',
                'is_required' => false,
            ]);

            ProductOptionValue::create(['product_option_id' => $extraOption->id, 'name' => 'Extra Smashed Angus Patty', 'price' => 3.99, 'is_default' => false]);
            ProductOptionValue::create(['product_option_id' => $extraOption->id, 'name' => 'Triple Applewood Smoked Bacon', 'price' => 2.49, 'is_default' => false]);
            ProductOptionValue::create(['product_option_id' => $extraOption->id, 'name' => 'Crispy Fried Jalapeño Wheels', 'price' => 0.99, 'is_default' => false]);
            ProductOptionValue::create(['product_option_id' => $extraOption->id, 'name' => 'Caramelized Balsamic Onions', 'price' => 0.99, 'is_default' => false]);
        }
    }
}
