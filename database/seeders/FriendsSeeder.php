<?php

namespace Database\Seeders;

use App\Models\achievements\AchievementProgress;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Sanctum\Sanctum;

class FriendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach (range(1, 100) as $i) {
            $pair = $users->random(2);
            $status = fake()->randomElement(['pending', 'friend', 'block']);

            Friend::firstOrCreate(
                [
                    'sender' => $pair[0]->id,
                    'receiver' => $pair[1]->id,
                ],
                [
                    'status' => $status,
                ]
            );

            Sanctum::actingAs($pair[0]);
            if ($status == 'friend') {
            AchievementProgress::progressChain('friends', 1);
            }
            else if ($status == 'block') {
                AchievementProgress::progress('block-user', 1);
            }
        }
    }
}
