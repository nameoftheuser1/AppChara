<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word, // Generates a random word for the product name
            'price' => $this->faker->randomFloat(2, 10, 100), // Random price between 10 and 100
            'img_path' => $this->faker->imageUrl(640, 480, 'products', true), // Random image URL
        ];
    }
}
