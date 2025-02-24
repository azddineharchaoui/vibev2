<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FriendshipController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/search', [UserSearchController::class, 'index'])->name('search.index');
    Route::post('/search', [UserSearchController::class, 'search'])->name('search.perform');

    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/friends/request/{friendId}', [FriendshipController::class, 'sendRequest'])->name('friends.request');
    Route::post('/friends/accept/{friendshipId}', [FriendshipController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/reject/{friendshipId}', [FriendshipController::class, 'rejectRequest'])->name('friends.reject');
    Route::get('/friends/search', [FriendshipController::class, 'search'])->name('friends.search');
    Route::get('/friends/requests', [FriendshipController::class, 'pendingRequests'])->name('friends.requests');
    Route::get('/friends', [FriendshipController::class, 'friendsList'])->name('friends.list');
});