<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
	use SoftDeletes, HasFactory;

	protected $fillable = ['user_id', 'title', 'description', 'image_path'];

	protected $appends = ['image_url'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function likes(): MorphMany
	{
		return $this->morphMany(Like::class, 'likeable');
	}

	public function isLikedBy($user): bool
	{
		if (!$user) return false;
		return $this->likes()->where('user_id', $user->id)->exists();
	}

	public function getImageUrlAttribute(): ?string
	{
		return $this->image_path ? asset('storage/' . $this->image_path) : null;
	}
}
