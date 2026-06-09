<?php

namespace App\Http\Controllers;

use App\Models\achievements\AchievementProgress;
use App\Models\Friend;
use App\Models\Leaderboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaderBoardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $latest = Leaderboard::latest();
        $increment = $latest->first()?->increment;
        $increment++;

        // Get the 10 users with the most points
        $topUsers = \App\Models\User::withSum('workouts', 'points')
            ->orderByDesc('workouts_sum_points')
            ->take(10)
            ->get();

        // Achievements 
        $topUser = $topUsers[0];
        AchievementProgress::progress('leaderboard-global-1', 1, $topUser->id);

        // Create leaderboard entries for these users
        foreach ($topUsers as $user) {
            if ($user->workouts_sum_points == null) {
                $user->workouts_sum_points = 0;
            }

            \App\Models\Leaderboard::create([
                'user_id' => $user->id,
                'position' => $user->workouts_sum_points, 
                'increment' => $increment
            ]);

            AchievementProgress::progress('leaderboard-entered', 1, $user->id);
        }
        Log::info(AchievementProgress::all());
        $leaderboard = Leaderboard::where('increment',$increment)->get();
        return $leaderboard;
    }

    /**
     * Display the leaderboard globally or on a per-user-base.
     */
    public function showGlobal()
    {
        $latest = Leaderboard::latest();
        $increment = $latest->first()?->increment;

        $leaderboard = Leaderboard::with('user')
            ->where('increment', $increment)
            ->get();        
        
        return $leaderboard;
    }

    public function showFriends()
    {
        $user = Auth::user();

        $friendships = Friend::getFriends($user->id);
        if ($friendships->count() < 1) {
            return response()->json([
                'message' => 'You should get some friends first..'
            ], 400);
        }

        $users = \App\Models\User::withSum('workouts', 'points')
        ->whereIn('id', $friendships->pluck('id')->all())
        ->orderByDesc('workouts_sum_points')
        ->get();

        $entries = [];
        foreach ($users as $user) {
            $entry = new Leaderboard();
            $entry->user_id = $user->id;
            $entry->position = $user->workouts_sum_points;
            $entry->increment = 0; // not required but cannot be null
            $entry->user = $user; // im lazy and want to go home 

            array_push($entries, $entry);
        }

        return $entries;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leaderboard = Leaderboard::find($id);
        $leaderboard->delete();

        return response()->json([
            'message' => 'Leaderboard entry deleted.'
        ], 200);
    }
}
