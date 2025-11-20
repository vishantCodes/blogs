<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Ensure users exist before creating blogs
		if (User::count() === 0) {
			$this->command->error('No users found. Run UserSeeder first.');
			return;
		}

		// Create blogs owned by random users
		Blog::factory()
			->count(50)
			->create([
				'user_id' => fn() => User::inRandomOrder()->first()->id,
			]);

		$this->command->info('Blogs seeded.');
	}
}
