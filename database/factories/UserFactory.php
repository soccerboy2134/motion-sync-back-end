<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random gender, then generate the name based of that
        // Generate random date of birth (max 70 years, min 15 years), then decide if private
        $gender = fake()->randomElement(['male', 'female', 'x']);
        $date_of_birth = fake()->dateTimeBetween('-70 years', '-15 years');
        $isAdult = date('Y') - $date_of_birth->format('Y') > 18;
        $isPrivate = true;
        if ($isAdult) {
            $isPrivate = fake()->randomElement([true, false]);
        }

        return [
            'name' => fake()->firstName($gender),
            'display_name' => fake()->userName(),
            'gender' => $gender,
            'date_of_birth' => $date_of_birth,
            'visibility' => $isPrivate,
            'password' => static::$password ??= Hash::make('GGG'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
