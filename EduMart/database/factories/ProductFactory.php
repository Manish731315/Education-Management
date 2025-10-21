<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        $type = $this->faker->randomElement(['course', 'ebook', 'material']);
        $price = $this->faker->randomFloat(2, 10, 500);
        $hasDiscount = $this->faker->boolean(30); // 30% chance of having discount
        
        return [
            'title' => $title,
            'slug' => \Str::slug($title),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $price,
            'discount_price' => $hasDiscount ? $this->faker->randomFloat(2, 5, $price * 0.8) : null,
            'thumbnail' => 'https://picsum.photos/400/300?random=' . $this->faker->numberBetween(1, 1000),
            'images' => json_encode([
                'https://picsum.photos/400/300?random=' . $this->faker->numberBetween(1, 1000),
                'https://picsum.photos/400/300?random=' . $this->faker->numberBetween(1, 1000),
            ]),
            'type' => $type,
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'content' => $this->faker->paragraphs(5, true),
            'file_path' => $this->faker->optional(0.7)->filePath(),
            'duration' => $type === 'course' ? $this->faker->numberBetween(30, 600) : null,
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'sold_count' => $this->faker->numberBetween(0, 50),
        ];
    }
}
