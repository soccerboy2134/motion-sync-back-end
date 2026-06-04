<?php

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

            Friend::firstOrCreate(
                [
                    'sender' => $pair[0]->id,
                    'receiver' => $pair[1]->id,
                ],
                [
                    'status' => fake()->randomElement([
                        'pending',
                        'friend',
                        'block',
                    ]),
                ]
            );
        }
    }
}
