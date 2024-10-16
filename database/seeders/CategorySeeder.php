<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);
        Category::create([
            'name' => 'ReactJs',
            'slug' => 'reactjs',
        ]);
        Category::create([
            'name' => 'Data Science',
            'slug' => 'data-science',
        ]);
        Category::create([
            'name' => 'Fullstack Developer',
            'slug' => 'fullstack-developer',
        ]);
    }
}
