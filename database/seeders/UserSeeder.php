<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		User::factory()
			->count(50)
			->create();
		User::create([
			'name' => 'Test user',
			'email' => 'test@user.com',
			'email_verified_at' => now(),
			'password' => Hash::make('password'),
			'remember_token' => Str::random(10),
		]);
	}
}
