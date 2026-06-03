<?php

use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkOutController;
use App\Models\WorkOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Unprotected (auth)
Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
Route::post('/user/authenticate', [UserController::class, 'authenticate'])->name('user.authenticate');

// sanctum hates us if we don't have a login method. It overrides it, and gives its own response. 
// it still hates us if we don't have it.. so here it is sanctum (we could override their override, but i dont want to)
Route::get('/test', function() {
    return response()->json(['message' => 'You should never see this']);
})->name('login');

// Auth
Route::group(['middleware' => 'auth:sanctum'], function () {
    // User 
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::patch('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // Theme
    Route::get('/theme/', [ThemeController::class, 'index'])->name('theme.index');

    // WorkOut
    Route::post('/workout/store', [WorkOutController::class, 'store'])->name('workout.store');
    Route::delete('/workout/{id}', [WorkOutController::class, 'destroy'])->name('workout.destroy');

    Route::group(['middleware' => 'IsAdmin'], function() {
        Route::post('/theme/store', [ThemeController::class, 'store'])->name('theme.store');
        Route::delete('/theme/{id}', [ThemeController::class, 'destroy'])->name('theme.destroy');
    });
    // new middleware group soonTM (validates only some users can do this)

    // Route::resource('/user', UserController::class)->only(['store', 'authen']);
    // Route::resource('/theme', ThemeController::class);
});

Route::post('/test', [App\Http\Controllers\ThemeController::class, 'store'])->name('theme.store');
