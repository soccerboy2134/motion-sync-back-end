<?php

namespace Database\Seeders;

use App\Models\achievements\AchievementProgress;
use App\Models\Leaderboard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Sanctum\Sanctum;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

            // Give them achievements
            Sanctum::actingAs($user);
            AchievementProgress::progressChain('leaderboard-entered', 1);
        }

        // Get top user 
        $topUser = $topUsers->first();
        Sanctum::actingAs($topUser);
        AchievementProgress::progress('leaderboard-global-1', 1, $topUser->id);
    }
}
