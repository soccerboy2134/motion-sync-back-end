<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
        $latest = Leaderboard::latest();
        $increment = $latest->first()?->increment;
        $increment++;

        // Get the 10 users with the most points
        $topUsers = \App\Models\User::withSum('workouts', 'points')
            ->orderByDesc('workouts_sum_points')
            ->take(10)
            ->get();

        // Create leaderboard entries for these users
        foreach ($topUsers as $user) {
            \App\Models\Leaderboard::create([
                'user_id' => $user->id,
                'position' => $user->workouts_sum_points, 
                'increment' => $increment
        ]);
        }
        
        $leaderboard = LeaderBoard::where('increment',$increment)->get();
        return $leaderboard;
    }




    /**
     * Display the specified resource.
     */
    public function showGlobal()
    {
        $latest = Leaderboard::latest();
        $increment = $latest->first()?->increment;

        $leaderboard = LeaderBoard::where('increment',$increment)->get();
        return $leaderboard;
    }

    public function showfriends()
    {

    }

   

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leaderboard = LeaderBoard::find($id);
        $leaderboard->delete();

        return response()->json([
'message' => 'Your leaderboard was successfully deleted.'
        ],200);
    }
}
