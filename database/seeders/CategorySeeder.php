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
                'name' => 'Sides',
                'slug' => 'sides',
                'description' => 'Crisp golden fries, crunchy onion rings, and seasoned chicken bites.',
                'icon' => '🍟',
                'sort_order' => 2,
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'description' => 'Chilled soft drinks, bottled spring water, and freshly squeezed juices.',
                'icon' => '🥤',
                'sort_order' => 3,
            ],
            [
                'name' => 'Meals',
                'slug' => 'meals',
                'description' => 'Complete signature feasts featuring burger, side, and chilled beverage.',
                'icon' => '👑',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
