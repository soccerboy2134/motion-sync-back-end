<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkOut;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    // public function definition(): array
    // {
    //     // Yes, UserIds can be grabbed in a much better way BUT it is just not optimized.
    //     // I don't want to murder my PC (they are innocent!) and this works. 
    //     // We know theres 100 seeded users anyway...

    //     $length = fake()->numberBetween(1000, 10000);
    //     $speed = fake()->numberBetween(1, 7) . '.' . fake()->numberBetween(0, 9);
    //     $type = fake()->randomElement(['walking', 'running']);
    //     $points = $length * $speed;
    //     $points = ($type == 'running') ? $points * 2 : $points;

    //     return [
    //         'user_id' => fake()->numberBetween(1, 100),
    //         'length' => $length,
    //         'speed' => $speed,
    //         'type' => $type,
    //         'points' => $points,
    //     ];
    // }
    public function definition(): array
    {
        // Yes, UserIds can be grabbed in a much better way BUT it is just not optimized.
        // I don't want to murder my PC (they are innocent!) and this works. 
        // We know theres 100 seeded users anyway...
        
        $length = fake()->numberBetween(1000, 10000);

        $speed = fake()->randomFloat(1, 2, 15);

        $type = match (true) {
            $speed < 4.5 => 'walking',
            $speed < 10.0 => 'running',
            default => 'sprinting',
        };

        $multiplier = match ($type) {
            'walking' => 1,
            'running' => 2,
            'sprinting' => 3,
        };

        $points = round(($length * $speed * $multiplier) / 100);

        return [
            'user_id' => fake()->numberBetween(1, 100),
            'length' => $length,
            'speed' => $speed,
            'type' => $type,
            'points' => $points,
        ];
    }
}
