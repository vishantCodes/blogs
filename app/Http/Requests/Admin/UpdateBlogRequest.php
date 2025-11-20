<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return Auth::check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'title' => ['sometimes', 'string', 'max:255'],
			'description' => ['sometimes', 'string'],
			'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
		];
	}

	/**
	 * Get custom messages for validator errors.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'title.string' => 'The title must be a valid text string.',
			'title.max' => 'The title cannot exceed 255 characters.',

			'description.string' => 'The description must be a valid text string.',
			'description.min' => 'The description must be at least 10 characters.',

			'image.image' => 'The file must be an image.',
			'image.mimes' => 'The image must be of type: jpeg, png, jpg, gif, webp.',
			'image.max' => 'The image size cannot exceed 2MB.',
		];
	}


	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array<string, string>
	 */
	public function attributes(): array
	{
		return [
			'title' => 'blog title',
			'description' => 'blog description',
			'image' => 'blog image',
		];
	}
}
