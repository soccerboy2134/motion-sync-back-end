<?php

namespace Database\Seeders;

use App\Models\Leaderboard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $latest = Leaderboard::latest();
        $increment = $latest->first()?->position;
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
    }
}
