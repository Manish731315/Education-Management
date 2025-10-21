<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Programming', 'Web Development', 'Mobile Development', 'Data Science',
            'Machine Learning', 'Design', 'Business', 'Marketing', 'Photography',
            'Music', 'Language Learning', 'Mathematics', 'Science', 'History'
        ]);

        return [
            'name' => $name,
            'slug' => \Str::slug($name),
            'description' => $this->faker->paragraph(3),
            'image' => null,
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
