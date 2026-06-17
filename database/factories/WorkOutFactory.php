<?php

namespace Database\Factories;

use App\Models\achievements\AchievementProgress;
use App\Models\User;
use App\Models\WorkOut;
use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Sanctum\Sanctum;

/**
 * @extends Factory<WorkOut>
 */
class WorkOutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Yes, UserIds can be grabbed in a much better way BUT it is just not optimized.
        // I don't want to murder my PC (they are innocent!) and this works. 
        // We know theres 100 seeded users anyway...
        
        $length = fake()->numberBetween(1000, 10000);

        $speed = fake()->randomFloat(1, 2, 15);

        $type = match (true) {
            $speed < 6 => 'walking',
            $speed < 12 => 'running',
            default => 'sprinting',
        };

        $multiplier = match ($type) {
            'walking' => 1,
            'running' => 2,
            'sprinting' => 3,
        };

        $points = round(($length * $speed * $multiplier) / 100);

        $user = User::find(fake()->numberBetween(1, 100));
        Sanctum::actingAs($user);

        AchievementProgress::progressChain('workout', 1);
        AchievementProgress::progressChain('total-distance', $length);
        if ($type == 'walking') {
            AchievementProgress::progressChain('walks', 1);
        }
        else if ($type == 'running') {
            AchievementProgress::progressChain('runs', 1);
        }
        else if ($type == 'sprinting') {
            AchievementProgress::progressChain('sprints', 1);
        }

        return [
            'user_id' => $user->id,
            'length' => $length,
            'speed' => $speed,
            'type' => $type,
            'points' => $points,
        ];
    }
}
