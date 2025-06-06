<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Admin\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Create 5 parent categories
        for ($i = 1; $i <= 5; $i++) {
            $name = $faker->unique()->word();
            $parentId = Category::insertGetId([
                'name' => ucfirst($name),
                'slug' => Str::slug($name),
                'excerpt' => $faker->sentence(),
                'parent_id' => null,
                'model' => null,
                'active' => $faker->boolean(90),
                'featured' => $faker->boolean(30),
                'position' => $i,
                'icon' => $faker->randomElement(['fa-star', 'fa-heart', 'fa-bolt', null]),
                'color' => $faker->safeHexColor(),
                'meta_name' => $faker->sentence(3),
                'meta_description' => $faker->sentence(6),
                'meta_keywords' => implode(', ', $faker->words(5)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create 3 child categories for each parent
            for ($j = 1; $j <= 3; $j++) {
                $childName = $faker->unique()->word();
                Category::insert([
                    'name' => ucfirst($childName),
                    'slug' => Str::slug($childName),
                    'excerpt' => $faker->sentence(),
                    'parent_id' => $parentId,
                    'model' => null,
                    'active' => $faker->boolean(90),
                    'featured' => $faker->boolean(20),
                    'position' => $j,
                    'icon' => $faker->randomElement(['fa-leaf', 'fa-moon', null]),
                    'color' => $faker->safeHexColor(),
                    'meta_name' => $faker->sentence(3),
                    'meta_description' => $faker->sentence(6),
                    'meta_keywords' => implode(', ', $faker->words(5)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
