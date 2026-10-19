<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Burgers',
                'slug' => 'burgers',
                'description' => 'Smashed prime Angus beef, artisan brioche, and our signature melted cheddar cascades.',
                'icon' => '🍔',
                'sort_order' => 1,
            ],
            [
                'name' => 'Fries',
                'slug' => 'fries',
                'description' => 'Crisp Idaho russets, double-fried to golden perfection and seasoned with chef blends.',
                'icon' => '🍟',
                'sort_order' => 2,
            ],
            [
                'name' => 'Signature Sauces',
                'slug' => 'signature-sauces',
                'description' => 'House-crafted dipping sauces, liquid gold melted cheddar, and gourmet aiolis.',
                'icon' => '🧀',
                'sort_order' => 3,
            ],
            [
                'name' => 'Combos and Meals',
                'slug' => 'combos-and-meals',
                'description' => 'Signature burger, loaded side, artisan dipping sauce, and an ice-cold beverage.',
                'icon' => '🥤',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
