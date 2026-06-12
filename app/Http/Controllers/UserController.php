<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\achievements\Achievement;
use App\Models\achievements\AchievementProgress;
use App\Models\UnlockedSkin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Responses should be standardised, but theres a bit more before that (mainly modifying the login route?)

    public function index() {
        $id = Auth::user()->id;

        $user = \App\Models\User::withSum('workouts', 'points')
            ->orderByDesc('workouts_sum_points')
            ->take(1)
            ->where('id', $id)
            ->get();

        return $user;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $request = $request->validated();

        $user = User::create($request);

        // do not show this in the documentation.
        Auth::login($user);
        AchievementProgress::progress('join-motionsync', 1);
        Auth::logout();

        $token = $user->createToken('pat')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => "bearer",
            'user' => $user,
        ]);
    }

    public function authenticate(AuthenticateUserRequest $request)
    {
        $request = $request->validated();

        // should return this as errorbag?
        if (!Auth::attempt($request)) {
            return response()->json([
                'message' => "Your name/password combination is not valid.",
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        $token = $user->createToken('pat')->plainTextToken;

        UnlockedSkin::firstOrCreate([
            'user_id' => $user->id,
            'skin_id' => 1, // default skin
        ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user->visibility == true || $user->id == Auth::user()->id) {
            $user->achievements = Achievement::getAchievementsWithProgress();
            return $user;
        }
        else {
            return response()->json([
                'message' => "This user's profile is private."
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $request = $request->validated();
        
        $user = User::find($id);
        $user->update($request);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->id != $id) {
            return response()->json([
                'message' => 'You are not authorized to delete this account.',
            ], 403);
        }

        $user = User::find($id);
        $user->delete();

        return response()->json([
            'message' => 'Your account was successfully deleted.'
        ], 200);
    }
}
