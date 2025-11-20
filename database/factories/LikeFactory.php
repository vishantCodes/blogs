<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
	protected $model = Like::class;

	public function definition(): array
	{
		return [
			'user_id' => User::factory(),
		];
	}

	// Attach like to a blog (polymorphic)
	public function forBlog(Blog $blog): static
	{
		return $this->state(fn() => [
			'likeable_id' => $blog->id,
			'likeable_type' => Blog::class,
		]);
	}

	// Assign user (your custom state)
	public function byUser($user): static
	{
		return $this->state(fn() => [
			'user_id' => $user->id,
		]);
	}
}
