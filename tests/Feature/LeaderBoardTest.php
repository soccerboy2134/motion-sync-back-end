<?php

use App\Models\Leaderboard as LeaderboardModel;
use App\Models\User;
use App\Models\WorkOut;
use App\Models\achievements\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');

test('Migrations include leaderboards', function () {
    Artisan::call('migrate:fresh');

    $this->assertTrue(Schema::hasTable('leaderboards'));
});

test('Store creates leaderboard entries for admin and forbids non-admins', function () {
    Artisan::call('migrate:fresh');

    // create users with workouts
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    WorkOut::factory()->create(['user_id' => $user1->id, 'points' => 100]);
    WorkOut::factory()->create(['user_id' => $user2->id, 'points' => 200]);
    WorkOut::factory()->create(['user_id' => $user3->id, 'points' => 50]);

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    // Seed achievements used by LeaderBoardController
    Achievement::create(['name' => 'Global Leader', 'description' => 'Top global leaderboard', 'slug' => 'leaderboard-global-1', 'points' => 1]);
    Achievement::create(['name' => 'Entered Leaderboard', 'description' => 'Entered the leaderboard', 'slug' => 'leaderboard-entered', 'points' => 1]);

    // non-admin cannot create leaderboard
    Sanctum::actingAs($user);
    $resp1 = $this->postJson('/api/leaderboard');
    $resp1->assertStatus(403)->assertJsonFragment(['message' => 'BAD. your not allowed to be here.']);

    // admin can create
    Sanctum::actingAs($admin);
    $resp2 = $this->postJson('/api/leaderboard');
    $resp2->assertStatus(200);
    $this->assertDatabaseHas('leaderboards', ['increment' => 1]);

    // Top user should receive the global leaderboard achievement
    $global = Achievement::where('slug', 'leaderboard-global-1')->first();
    $entered = Achievement::where('slug', 'leaderboard-entered')->first();
    $this->assertDatabaseHas('achievement_progress', [
        'user_id' => $user2->id,
        'achievement_id' => $global->id,
        'is_unlocked' => true,
    ]);

    // All users entered should have the 'entered' achievement
    foreach ([$user1->id, $user2->id, $user3->id] as $uid) {
        $this->assertDatabaseHas('achievement_progress', [
            'user_id' => $uid,
            'achievement_id' => $entered->id,
            'is_unlocked' => true,
        ]);
    }
});

test('showGlobal returns latest leaderboard entries', function () {
    Artisan::call('migrate:fresh');

    $userA = User::factory()->create();
    $userB = User::factory()->create();

    WorkOut::factory()->create(['user_id' => $userA->id, 'points' => 300]);
    WorkOut::factory()->create(['user_id' => $userB->id, 'points' => 150]);

    $admin = User::factory()->create(['role' => 'admin']);
    // Seed achievements used by LeaderBoardController
    Achievement::create(['name' => 'Global Leader', 'description' => 'Top global leaderboard', 'slug' => 'leaderboard-global-1', 'points' => 1]);
    Achievement::create(['name' => 'Entered Leaderboard', 'description' => 'Entered the leaderboard', 'slug' => 'leaderboard-entered', 'points' => 1]);

    Sanctum::actingAs($admin);
    $this->postJson('/api/leaderboard')->assertStatus(200)->assertJsonCount(3);

    $global = Achievement::where('slug', 'leaderboard-global-1')->first();
    $entered = Achievement::where('slug', 'leaderboard-entered')->first();

    // Top user (userA) should have the global achievement
    $this->assertDatabaseHas('achievement_progress', [
        'user_id' => $userA->id,
        'achievement_id' => $global->id,
        'is_unlocked' => true,
    ]);

    // All users returned (userA, userB, admin) should have the 'entered' achievement
    foreach ([$userA->id, $userB->id, $admin->id] as $uid) {
        $this->assertDatabaseHas('achievement_progress', [
            'user_id' => $uid,
            'achievement_id' => $entered->id,
            'is_unlocked' => true,
        ]);
    }

    // any authenticated user can view the global leaderboard
    $viewer = User::factory()->create();
    Sanctum::actingAs($viewer);
    $resp = $this->getJson('/api/leaderboard');
    $resp->assertStatus(200);
    $this->assertDatabaseHas('leaderboards', ['increment' => 1]);
});

test('showFriends returns 400 when user has no friends', function () {
    Artisan::call('migrate:fresh');

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $resp = $this->getJson('/api/leaderboard/friends');
    $resp->assertStatus(400)->assertJsonFragment(['message' => 'You should get some friends first..']);
});

test('destroy removes leaderboard entry for admin and forbids non-admins', function () {
    Artisan::call('migrate:fresh');

    $entry = LeaderboardModel::create([
        'user_id' => 1,
        'position' => 100,
        'increment' => 0,
    ]);

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    // non-admin cannot delete
    Sanctum::actingAs($user);
    $resp1 = $this->deleteJson("/api/leaderboard/{$entry->id}");
    $resp1->assertStatus(403)->assertJsonFragment(['message' => 'BAD. your not allowed to be here.']);

    // admin can delete
    Sanctum::actingAs($admin);
    $resp2 = $this->deleteJson("/api/leaderboard/{$entry->id}");
    $resp2->assertStatus(200)->assertJsonFragment(['message' => 'Leaderboard entry deleted.']);
    $this->assertDatabaseMissing('leaderboards', ['id' => $entry->id]);
});
