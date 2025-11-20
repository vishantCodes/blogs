<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
	use AuthorizesRequests;
	/**
	 * Get list of blogs with pagination, filters, and search
	 */
	public function index(Request $request): JsonResponse
	{
		try {
			$query = Blog::query()
				->with('user:id,name,email')
				->withCount('likes')
				->when($request->filled('search'), function ($q) use ($request) {
					$search = $request->search;
					$q->where('title', 'like', "%{$search}%")
						->orWhere('description', 'like', "%{$search}%");
				})
				->when($request->sort_by === 'most_liked', fn($q) => $q->orderByDesc('likes_count'))
				->when($request->sort_by === 'latest' || !$request->filled('sort_by'), fn($q) => $q->latest());

			$perPage = $request->integer('per_page', 10);
			$blogs = $query->paginate($perPage);

			// Add is_liked_by_user flag using lazy collection
			$blogs->through(function ($blog) {
				$blog->is_liked_by_user = $blog->likes()
					->where('user_id', auth()->id())
					->exists();
				return $blog;
			});

			return response()->json([
				'success' => true,
				'message' => 'Blogs retrieved successfully',
				'data' => $blogs
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'An error occurred while fetching blogs',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Create a new blog
	 */
	public function store(StoreBlogRequest $request): JsonResponse
	{
		try {
			// Check authorization
			$this->authorize('create', Blog::class);
			$validated = $request->validated();
			$imagePath = $request->file('image')?->store('blogs', 'public');
			$blog = auth()->user()->blogs()->create([
				...$validated,
				'image' => $imagePath
			]);
			return response()->json([
				'success' => true,
				'message' => 'Blog created successfully',
				'data' => $blog->load('user:id,name,email')
			], 201);
		} catch (ValidationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Validation error',
				'errors' => $e->errors()
			], 422);
		} catch (AuthorizationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Unauthorized action',
				'error' => $e->getMessage()
			], 403);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'An error occurred while creating the blog',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Get a single blog
	 */
	public function show(Blog $blog): JsonResponse
	{
		try {
			$blog->load('user:id,name,email')
				->loadCount('likes');

			// Check if liked by current user
			$blog->is_liked_by_user = $blog->isLikedBy(auth()->user());

			return response()->json([
				'success' => true,
				'message' => 'Blog retrieved successfully',
				'data' => $blog
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Blog not found',
				'error' => $e->getMessage()
			], 404);
		}
	}

	/**
	 * Update a blog
	 */
	public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
	{
		try {
			// Check authorization using Policy
			$this->authorize('update', $blog);

			$validated = $request->validated();

			// Handle image upload if present
			if ($request->hasFile('image')) {
				// Delete old image
				if ($blog->image) {
					Storage::disk('public')->delete($blog->image);
				}
				// Store new image and add to validated data
				$validated['image'] = $request->file('image')->store('blogs', 'public');
			}

			// Update blog
			$blog->update($validated);

			return response()->json([
				'success' => true,
				'message' => 'Blog updated successfully',
				'data' => $blog->fresh('user:id,name,email')
			]);
		} catch (ValidationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Validation error',
				'errors' => $e->errors()
			], 422);
		} catch (AuthorizationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Unauthorized action',
				'error' => $e->getMessage()
			], 403);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'An error occurred while updating the blog',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Delete a blog
	 */
	public function destroy(Blog $blog): JsonResponse
	{
		try {
			// Check authorization using Policy
			$this->authorize('delete', $blog);

			// Delete image from storage
			if ($blog->image) {
				Storage::disk('public')->delete($blog->image);
			}

			// Delete blog
			$blog->delete();

			return response()->json([
				'success' => true,
				'message' => 'Blog deleted successfully'
			]);
		} catch (AuthorizationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Unauthorized action',
				'error' => $e->getMessage()
			], 403);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'An error occurred while deleting the blog',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Toggle like on a blog
	 */
	public function toggleLike(Blog $blog): JsonResponse
	{
		try {
			$user = auth()->user();

			// Toggle like using polymorphic relationship
			$like = $blog->likes()->where('user_id', $user->id)->first();

			if ($like) {
				// Unlike
				$like->delete();
				$message = 'Blog unliked successfully';
				$isLiked = false;
			} else {
				// Like
				$blog->likes()->create([
					'user_id' => $user->id
				]);
				$message = 'Blog liked successfully';
				$isLiked = true;
			}

			// Get updated likes count
			$likesCount = $blog->likes()->count();

			return response()->json([
				'success' => true,
				'message' => $message,
				'data' => [
					'is_liked' => $isLiked,
					'likes_count' => $likesCount
				]
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'An error occurred while toggling like',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
