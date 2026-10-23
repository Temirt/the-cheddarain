<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $burgersCat = Category::where('slug', 'burgers')->first();
        $friesCat = Category::where('slug', 'fries')->first();
        $saucesCat = Category::where('slug', 'signature-sauces')->first();
        $combosCat = Category::where('slug', 'combos-and-meals')->first();

        $products = [
            // BURGERS
            [
                'category_id' => $burgersCat->id,
                'name' => 'The Ultimate Cheddar Lava',
                'slug' => 'the-ultimate-cheddar-lava',
                'description' => 'Double smashed Angus beef patties, molten cheddar waterfall, caramelized balsamic onions, and house-cured pickles on toasted artisanal brioche.',
                'price' => 14.99,
                'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Signature',
                'calories' => 890,
                'prep_time_minutes' => 14,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $burgersCat->id,
                'name' => 'The Smokey Truffle Melt',
                'slug' => 'the-smokey-truffle-melt',
                'description' => 'Double prime beef, aged smoked Wisconsin cheddar, garlic butter sautéed cremini mushrooms, and black truffle crema on a glazed brioche bun.',
                'price' => 16.49,
                'image_url' => 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Chef Special',
                'calories' => 940,
                'prep_time_minutes' => 15,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $burgersCat->id,
                'name' => 'The Classic Double Smash',
                'slug' => 'the-classic-double-smash',
                'description' => 'Twin crispy-edged smashed beef patties, double American cheddar slices, butterhead lettuce, vine-ripened tomato, and our secret Cheddarain sauce.',
                'price' => 12.99,
                'image_url' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Best Seller',
                'calories' => 760,
                'prep_time_minutes' => 12,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'category_id' => $burgersCat->id,
                'name' => 'The Fiery Jalapeño Crunch',
                'slug' => 'the-fiery-jalapeno-crunch',
                'description' => 'Molten pepper jack and sharp cheddar infusion, crispy fried jalapeño wheels, smoked habanero honey glaze, and pickled red onions.',
                'price' => 14.49,
                'image_url' => 'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Spicy',
                'calories' => 840,
                'prep_time_minutes' => 14,
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'category_id' => $burgersCat->id,
                'name' => 'The Bacon Mountain Royale',
                'slug' => 'the-bacon-mountain-royale',
                'description' => 'Triple thick-cut applewood smoked bacon, double sharp cheddar drip, crispy haystack onions, and Kentucky bourbon BBQ glaze.',
                'price' => 15.99,
                'image_url' => 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Popular',
                'calories' => 1020,
                'prep_time_minutes' => 16,
                'is_featured' => true,
                'sort_order' => 5,
            ],

            // FRIES
            [
                'category_id' => $friesCat->id,
                'name' => 'Loaded Cheddar Bacon Fries',
                'slug' => 'loaded-cheddar-bacon-fries',
                'description' => 'Hand-cut crispy Idaho russets drowned in our signature warm melted cheddar sauce, topped with crunchy smoked bacon bits and fresh scallions.',
                'price' => 7.99,
                'image_url' => 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Must Try',
                'calories' => 610,
                'prep_time_minutes' => 10,
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'category_id' => $friesCat->id,
                'name' => 'Crispy Cajun Curly Fries',
                'slug' => 'crispy-cajun-curly-fries',
                'description' => 'Spiral-cut potatoes tossed in aromatic New Orleans Cajun seasoning, served piping hot and crunchy.',
                'price' => 5.49,
                'image_url' => 'https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?auto=format&fit=crop&w=800&q=80',
                'badge' => null,
                'calories' => 450,
                'prep_time_minutes' => 8,
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'category_id' => $friesCat->id,
                'name' => 'Truffle Parmesan Fries',
                'slug' => 'truffle-parmesan-fries',
                'description' => 'Golden crisp-tossed fries drizzled with white truffle oil, shaved aged Italian parmesan, and freshly cracked black pepper.',
                'price' => 7.49,
                'image_url' => 'https://images.unsplash.com/photo-1585109649139-366815a0d713?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Gourmet',
                'calories' => 520,
                'prep_time_minutes' => 10,
                'is_featured' => true,
                'sort_order' => 8,
            ],

            // SIGNATURE SAUCES
            [
                'category_id' => $saucesCat->id,
                'name' => 'Liquid Gold Cheddar Dip Pot',
                'slug' => 'liquid-gold-cheddar-dip-pot',
                'description' => 'Our world-famous velvety smooth melted cheddar sauce served warm in an artisan dipping crock.',
                'price' => 2.49,
                'image_url' => 'https://images.unsplash.com/photo-1452195100486-9cc805987862?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Iconic',
                'calories' => 190,
                'prep_time_minutes' => 2,
                'is_featured' => true,
                'sort_order' => 9,
            ],
            [
                'category_id' => $saucesCat->id,
                'name' => 'Smoky Chipotle Aioli',
                'slug' => 'smoky-chipotle-aioli',
                'description' => 'Charred Mexican chipotle peppers folded into creamy garlic mayonnaise with a kiss of fresh lime.',
                'price' => 1.75,
                'image_url' => 'https://images.unsplash.com/photo-1472476443507-c7a5948772fc?auto=format&fit=crop&w=800&q=80',
                'badge' => null,
                'calories' => 140,
                'prep_time_minutes' => 2,
                'is_featured' => false,
                'sort_order' => 10,
            ],
            [
                'category_id' => $saucesCat->id,
                'name' => 'Truffle Garlic Crema',
                'slug' => 'truffle-garlic-crema',
                'description' => 'Black summer truffle essence whipped with roasted garlic crema and chives.',
                'price' => 2.25,
                'image_url' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Premium',
                'calories' => 160,
                'prep_time_minutes' => 2,
                'is_featured' => false,
                'sort_order' => 11,
            ],

            // COMBOS AND MEALS
            [
                'category_id' => $combosCat->id,
                'name' => 'The King Cheddar Feast',
                'slug' => 'the-king-cheddar-feast',
                'description' => 'The Ultimate Cheddar Lava Burger + Loaded Cheddar Bacon Fries + Liquid Gold Dip Pot + Choice of Ice-Cold Fountain Drink.',
                'price' => 22.99,
                'image_url' => 'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Best Value',
                'calories' => 1480,
                'prep_time_minutes' => 16,
                'is_featured' => true,
                'sort_order' => 12,
            ],
            [
                'category_id' => $combosCat->id,
                'name' => 'Smokey Truffle Gourmet Meal',
                'slug' => 'smokey-truffle-gourmet-meal',
                'description' => 'The Smokey Truffle Melt Burger + Truffle Parmesan Fries + Truffle Garlic Crema + Craft Cane Sugar Soda.',
                'price' => 24.49,
                'image_url' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80',
                'badge' => 'Luxury Combo',
                'calories' => 1420,
                'prep_time_minutes' => 18,
                'is_featured' => true,
                'sort_order' => 13,
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
