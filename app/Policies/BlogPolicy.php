<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
    /**
     * Determine whether the user can view any blogs.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the blog.
     */
    public function view(?User $user, Blog $blog): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create blogs.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the blog.
     */
    public function update(User $user, Blog $blog): Response
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You are not authorized to update this blog.');
    }

    /**
     * Determine whether the user can delete the blog.
     */
    public function delete(User $user, Blog $blog): Response
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You are not authorized to delete this blog.');
    }

    /**
     * Determine whether the user can restore the blog.
     */
    public function restore(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id;
    }

    /**
     * Determine whether the user can permanently delete the blog.
     */
    public function forceDelete(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id;
    }
}