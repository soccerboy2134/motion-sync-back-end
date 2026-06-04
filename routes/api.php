<?php

use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkOutController;
use App\Models\WorkOut;
use App\Services\DistanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 10Req per minute for guests, 100 for users

// Unprotected (auth)
Route::middleware('throttle:10,1')->group(function() {
    Route::post('/user/store', [UserController::class, 'store'])->middleware('throttle:10,1')->name('user.store');
    Route::post('/user/authenticate', [UserController::class, 'authenticate'])->middleware('throtle:10,1')->name('user.authenticate');

    // sanctum hates us if we don't have a login method. It overrides it, and gives its own response. 
    // it still hates us if we don't have it.. so here it is sanctum (we could override their override, but i dont want to)
    Route::get('/test', function() {
        return response()->json(['message' => 'You should never see this']);
    })->name('login');
});


// Auth
// Route::group(['middleware' => 'auth:sanctum'], function () {
Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function() {
    // User 
    Route::get('/user', function() { return Auth::user(); })->name('user.token');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::patch('/user/{id}', [UserController::class, 'update'])->name('user.update');

    // Theme
    Route::get('/theme/', [ThemeController::class, 'index'])->name('theme.index');

    // WorkOut
    Route::post('/workout/store', [WorkOutController::class, 'store'])->name('workout.store');
    Route::delete('/workout/{id}', [WorkOutController::class, 'destroy'])->name('workout.destroy');

    Route::group(['middleware' => 'IsAdmin'], function() {
        Route::post('/theme/store', [ThemeController::class, 'store'])->name('theme.store');
        Route::delete('/theme/{id}', [ThemeController::class, 'destroy'])->name('theme.destroy');
    });
});

Route::post('/test', [App\Http\Controllers\ThemeController::class, 'store'])->name('theme.store');

// debug
Route::get('/the-algoritm', function() {
    $input = [
        [ //51.451132621947316, 5.4779453558539695
        'lat' => '51.451132621947316',
        'lon' => '5.4779453558539695',
        'timestamp' => '2026-06-02 10:00:30'
        ],
        [ //51.45728286779253, 5.47683864940374
        'lat' => '51.45728286779253',
        'lon' => '5.47683864940374',
        'timestamp' => '2026-06-02 10:01:00'
        ],
        [ //51.457376459294984, 5.485121310962364
        'lat' => '51.457376459294984',
        'lon' => '5.485121310962364',
        'timestamp' => '2026-06-02 10:01:30'
        ],
        [ //51.45293733584598, 5.484520496123944
        'lat' => '51.45293733584598',
        'lon' => '5.484520496123944',
        'timestamp' => '2026-06-02 10:02:00'
        ],
        [ //51.45303616608521, 5.477906707146477
        'lat' => '51.45303616608521',
        'lon' => '5.477906707146477',
        'timestamp' => '2026-06-02 10:02:30'
        ],
    ];

    $result = DistanceService::calculateDistance($input);
    return $result;

    // $test1 = Carbon::parse('2026-06-02 10:02:30');
    // $test2 = Carbon::parse('2026-06-02 10:02:00');

    // return $test2->diffInSeconds($test1);
    // $this->comment($result);
});