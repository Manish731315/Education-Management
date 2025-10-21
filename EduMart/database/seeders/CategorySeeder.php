<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Learn various programming languages and concepts',
                'is_active' => true,
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Frontend and backend web development courses',
                'is_active' => true,
            ],
            [
                'name' => 'Mobile Development',
                'slug' => 'mobile-development',
                'description' => 'iOS and Android app development',
                'is_active' => true,
            ],
            [
                'name' => 'Data Science',
                'slug' => 'data-science',
                'description' => 'Data analysis, machine learning, and AI',
                'is_active' => true,
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX design, graphic design, and creative courses',
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business management, entrepreneurship, and finance',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing, SEO, and social media marketing',
                'is_active' => true,
            ],
            [
                'name' => 'Photography',
                'slug' => 'photography',
                'description' => 'Photography techniques and editing',
                'is_active' => true,
            ],
            [
                'name' => 'Music',
                'slug' => 'music',
                'description' => 'Music theory, instruments, and production',
                'is_active' => true,
            ],
            [
                'name' => 'Language Learning',
                'slug' => 'language-learning',
                'description' => 'Learn new languages and improve communication',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
