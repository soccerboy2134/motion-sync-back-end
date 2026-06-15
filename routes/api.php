<?php

use App\Http\Controllers\FriendController;
use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\SkinsController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkOutController;
use App\Models\achievements\AchievementProgress;
use App\Models\User;
use App\Models\WorkOut;
use App\Services\DistanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 10Req per minute for guests, 100 for users

// Unprotected (auth)
Route::middleware('throttle:10,1')->group(function() {
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::post('/user/authenticate', [UserController::class, 'authenticate'])->name('user.authenticate');

    // Theme
    Route::get('/theme/', [ThemeController::class, 'index'])->name('theme.index');

    // sanctum hates us if we don't have a login method. It overrides it, and gives its own response. 
    // it still hates us if we don't have it.. so here it is sanctum (we could override their override, but i dont want to)
    Route::get('/test', function() {
        return response()->json(['message' => 'You should never see this']);
    })->name('login');
});


// Auth
// Route::group(['middleware' => 'auth:sanctum'], function () {
Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function() {
Route::get('a', function() {
    AchievementProgress::progress('workout-1', 1000);
    AchievementProgress::progress('length-1000', 1000);
    AchievementProgress::progress('friends-100', 1000);
    AchievementProgress::progress('total-length-100000', 1000);
    AchievementProgress::progress('length-2500', 1000);
});

    // User 
    Route::get('/user', [UserController::class, 'index'])->name('user.token');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::patch('/user/{id}', [UserController::class, 'update'])->name('user.update');

    // WorkOut
    Route::get('/workout', [WorkOutController::class, 'index'])->name('workout.index');
    Route::get('/workout/{id}', [WorkOutController::class, 'show'])->name('workout.show');
    Route::post('/workout/store', [WorkOutController::class, 'store'])->name('workout.store');
    Route::delete('/workout/{id}', [WorkOutController::class, 'destroy'])->name('workout.destroy');

    // Friends 
    Route::get('/friend', [FriendController::class, 'index'])->name('friend.index');
    Route::post('/friend/{id}', [FriendController::class, 'store'])->name('friend.store');
    Route::put('/friend', [FriendController::class, 'update'])->name('friend.update');

    // Leaderboard
    Route::get('/leaderboard', [LeaderBoardController::class, 'showGlobal'])->name('leaderboard.global');
    Route::get('/leaderboard/friends', [LeaderBoardController::class, 'showFriends'])->name('leaderboard.friends');

    // Skin
    Route::get('/skins', [SkinsController::class, 'index'])->name('skins.index');
    Route::get('/skins/unlocked', [SkinsController::class, 'unlocked'])->name('skins.unlocked');
    Route::get('/skins/{id}', [SkinsController::class, 'show'])->name('skin.show');

    // Admin routes 
    Route::group(['middleware' => 'IsAdmin'], function() {
        Route::post('/theme/store', [ThemeController::class, 'store'])->name('theme.store');
        Route::delete('/theme/{id}', [ThemeController::class, 'destroy'])->name('theme.destroy');
        
        Route::post('/leaderboard', [LeaderBoardController::class, 'store'])->name('leaderboard.store');
        Route::delete('/leaderboard/{id}', [LeaderBoardController::class, 'destroy'])->name('leaderboard.destroy');
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

Route::get('/debug/{id}', function(string $id) {
    $user = User::find($id);
    $token = $user->createToken('pat')->plainTextToken;
    return $token;
});