<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'title' => fake()->sentence(6, true),
			'description' => fake()->paragraphs(3, true),
			'image_path' => $this->generateFakeImagePath(),
			'user_id' => User::factory(),
			'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
			'updated_at' => fn(array $attributes) => $attributes['created_at'],
		];
	}

	/**
	 * Generate a fake image path (you can replace with actual image generation)
	 */
	private function generateFakeImagePath(): string
	{
		return "blogs/blog-" . fake()->unique()->numberBetween(1, 10000) . ".jpg";
	}

	/**
	 * Indicate that the blog was created recently.
	 */
	public function recent(): static
	{
		return $this->state(fn(array $attributes) => [
			'created_at' => fake()->dateTimeBetween('-7 days', 'now'),
		]);
	}

	/**
	 * Indicate that the blog is popular (will be used to add more likes).
	 */
	public function popular(): static
	{
		return $this->state(fn(array $attributes) => [
			'created_at' => fake()->dateTimeBetween('-3 months', '-1 month'),
		]);
	}
}
