<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Food', 'Electronics', 'Clothing', 'Furniture', 'Services', 'Books', 'Sports', 'Other'];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category],
                ['slug' => Str::slug($category)]
            );
        }
    }
}
