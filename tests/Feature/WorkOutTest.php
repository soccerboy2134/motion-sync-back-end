<?php

use App\Models\WorkOut;
use App\Models\achievements\Achievement;
use App\Models\achievements\AchievementChainParent;
use App\Models\achievements\AchievementChainChild;
use App\Models\achievements\AchievementProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');

test('Store workout and award achievements', function () {
    Artisan::call('migrate:fresh');

    $user = App\Models\User::factory()->create();

    // Seed the achievements and chains for workouts and distance
    $this->seed(\Database\Seeders\AchievementSeeder::class);
    $this->seed(\Database\Seeders\BadgeSeeder::class);
    $this->seed(\Database\Seeders\SkinSeeder::class);

    $achievementWorkouts = Achievement::where('slug', 'workout-1')->first();
    $achievementTenWorkouts = Achievement::where('slug', 'workout-10')->first();
    $achievementDistance = Achievement::where('slug', 'total-length-2500')->first();

    Sanctum::actingAs($user);

    $payload = [
        'waypoints' => [
            [ //51.451132621947316, 5.4779453558539695
                'lat' => '51.451132621947316',
                'lon' => '5.4779453558539695',
                'timestamp' => '2026-06-02 10:00:30'
            ],
            [ //51.45728286779253, 5.47683864940374
                'lat' => '51.45728286779253',
                'lon' => '5.47683864940374',
                'timestamp' => '2026-06-02 10:01:00'
            ],
            [ //51.457376459294984, 5.485121310962364
                'lat' => '51.457376459294984',
                'lon' => '5.485121310962364',
                'timestamp' => '2026-06-02 10:01:30'
            ],
            [ //51.45293733584598, 5.484520496123944
                'lat' => '51.45293733584598',
                'lon' => '5.484520496123944',
                'timestamp' => '2026-06-02 10:02:00'
            ],
            [ //51.45303616608521, 5.477906707146477
                'lat' => '51.45303616608521',
                'lon' => '5.477906707146477',
                'timestamp' => '2026-06-02 10:02:30'
            ],
        ],
    ];

    $response = $this->postJson('/api/workout/store', $payload);

    $response->assertStatus(201)->assertJsonFragment(['user_id' => $user->id]);

    $this->assertDatabaseHas('work_outs', ['user_id' => $user->id]);

    $this->assertDatabaseHas('achievement_progress', [
        'user_id' => $user->id,
        'achievement_id' => $achievementWorkouts->id,
        'is_unlocked' => true,
    ]);

    $this->assertDatabaseHas('achievement_progress', [
        'user_id' => $user->id,
        'achievement_id' => $achievementDistance->id,
        'points' => 1528,
        'is_unlocked' => false,
    ]);

    $this->assertDatabaseHas('achievement_progress', [
        'user_id' => $user->id,
        'achievement_id' => $achievementTenWorkouts->id,
        'is_unlocked' => false,
    ]);

    // Assert the user got their skins
    $this->assertDatabaseHas('unlocked_skins', [
        'user_id' => $user->id,
        'skin_id' => $achievementWorkouts->skin_id,
    ]);
    $this->assertDatabaseHas('unlocked_skins', [
        'user_id' => $user->id,
        'skin_id' => $achievementDistance->skin_id,
    ]);
});

test('Store workout validation fails without waypoints', function () {
    Artisan::call('migrate:fresh');

    $user = App\Models\User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/workout/store', []);

    $response->assertStatus(422)->assertJsonValidationErrors(['waypoints']);
});

test('Owner can view workout; others cannot', function () {
    Artisan::call('migrate:fresh');

    $owner = App\Models\User::factory()->create();
    $other = App\Models\User::factory()->create();

    $workout = WorkOut::factory()->create(['user_id' => $owner->id]);

    Sanctum::actingAs($other);
    $response = $this->getJson("/api/workout/{$workout->id}");
    $response->assertStatus(403);

    Sanctum::actingAs($owner);
    $response = $this->getJson("/api/workout/{$workout->id}");
    $response->assertStatus(200)->assertJsonFragment(['id' => $workout->id]);
});

test('Owner can delete workout; others cannot', function () {
    Artisan::call('migrate:fresh');

    $owner = App\Models\User::factory()->create();
    $other = App\Models\User::factory()->create();

    $workout = WorkOut::factory()->create(['user_id' => $owner->id]);

    Sanctum::actingAs($other);
    $response = $this->deleteJson("/api/workout/{$workout->id}");
    $response->assertStatus(403);

    Sanctum::actingAs($owner);
    $response = $this->deleteJson("/api/workout/{$workout->id}");
    $response->assertStatus(200);

    $this->assertDatabaseMissing('work_outs', ['id' => $workout->id]);
});
