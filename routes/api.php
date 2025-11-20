<?php

use App\Http\Controllers\Admin\BlogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {

	// Auth routes
	Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
	Route::get('/me', [AuthController::class, 'me'])->name('api.me');

	// Blog routes
	Route::prefix('blogs')->name('api.blogs.')->group(function () {
		Route::get('/', [BlogController::class, 'index'])->name('index');
		Route::post('/', [BlogController::class, 'store'])->name('store');

		Route::get('/{blog}', [BlogController::class, 'show'])->name('show');
		Route::put('/{blog}', [BlogController::class, 'update'])->name('update');
		Route::patch('/{blog}', [BlogController::class, 'update'])->name('update.patch');

		Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');

		Route::post('/{blog}/like', [BlogController::class, 'toggleLike'])->name('like');
	});
});
