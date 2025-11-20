<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Blog;
use App\Models\Like;

class LikeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$this->command->info('Attaching likes to blogs...');
		$users = User::all();
		$blogs = Blog::all();

		if ($users->isEmpty()) {
			$this->command->error('No users found! Please run UserSeeder first.');
			return;
		}
		if ($blogs->isEmpty()) {
			$this->command->error('No blogs found! Please run BlogSeeder first.');
			return;
		}

		$totalLikes = 0;

		// Attach random likes to regular blogs
		foreach ($blogs as $blog) {
			// Each blog gets 0-15 random likes
			$likesCount = rand(0, 15);

			// Get random users to like this blog (excluding blog owner)
			$availableUsers = $users->where('id', '!=', $blog->user_id);

			if ($availableUsers->isEmpty()) {
				continue;
			}

			$likersCount = min($likesCount, $availableUsers->count());
			$likers = $availableUsers->random($likersCount);

			foreach ($likers as $liker) {
				// Check if this user hasn't already liked this blog
				if (!$blog->likes()->where('user_id', $liker->id)->exists()) {
					Like::factory()
						->forBlog($blog)
						->byUser($liker)
						->create();

					$totalLikes++;
				}
			}
		}

		$this->command->info("{$totalLikes} likes attached to blogs!");
	}
}
