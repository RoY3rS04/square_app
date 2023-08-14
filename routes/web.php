<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\SavedController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::redirect('/', 'register');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [RegisteredUserController::class, 'index']);

    Route::get('/friends', [RoutesController::class, 'friends']);

    Route::get('/photos', [RoutesController::class, 'photos']);

    Route::get('/profile', [RoutesController::class, 'profile']);

    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get('/people', [RoutesController::class, 'people']);

    Route::post('/posts', [PostController::class, 'store']);

    Route::post('/friends', [FriendController::class, 'store']);

    Route::post('/notifications', [NotificationController::class, 'store']);

    Route::post('/likes', [LikeController::class, 'store']);

    Route::post('/followers', [FollowerController::class, 'store']);

    Route::get('/posts/{post}',[PostController::class, 'show']);

    Route::post('/comments', [CommentController::class, 'store']);

    Route::post('/saveds', [SavedController::class, 'store']);

    Route::delete('/followers/{user}', [FollowerController::class, 'destroy']);

    Route::post('/followers/posts/{user}', [FollowerController::class, 'stopPostsFrom']);

    Route::post('/notifications/stop/{user}', [NotificationController::class, 'stopNotificationsFrom']);

    Route::delete('/notifications/{user}', [NotificationController::class, 'destroy']);

    Route::patch('/users/{user}', [UserController::class, 'update']);

    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::get('/posts/edit/{post}', [PostController::class, 'edit']);

    Route::patch('/posts/{post}', [PostController::class, 'update']);
});

require __DIR__.'/auth.php';
