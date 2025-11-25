<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
	/**
	 * Determine whether the user can view the list of blogs.
	 * Only authenticated users can access blog listings.
	 */
	public function viewAny(?User $user): bool
	{
		return $user !== null;
	}

	/**
	 * Determine whether the user can view a specific blog.
	 * Only authenticated users can view blogs.
	 */
	public function view(?User $user, Blog $blog): bool
	{
		return $user !== null;
	}

	/**
	 * Determine whether the authenticated user can create a blog.
	 * Any logged-in user is allowed to create blogs.
	 */
	public function create(User $user): bool
	{
		return true;
	}

	/**
	 * Determine whether the authenticated user can update the blog.
	 * Only the owner of the blog may update it.
	 */
	public function update(User $user, Blog $blog): Response
	{
		return $user->id === $blog->user_id
			? Response::allow()
			: Response::deny('You are not authorized to update this blog.');
	}

	/**
	 * Determine whether the authenticated user can delete the blog.
	 * Only the owner of the blog may delete it.
	 */
	public function delete(User $user, Blog $blog): Response
	{
		return $user->id === $blog->user_id
			? Response::allow()
			: Response::deny('You are not authorized to delete this blog.');
	}

	/**
	 * Determine whether the authenticated user can restore the blog.
	 * Only the owner may restore their blog.
	 */
	public function restore(User $user, Blog $blog): bool
	{
		return $user->id === $blog->user_id;
	}

	/**
	 * Determine whether the authenticated user can permanently delete the blog.
	 * Only the owner may force delete their blog.
	 */
	public function forceDelete(User $user, Blog $blog): bool
	{
		return $user->id === $blog->user_id;
	}
}
