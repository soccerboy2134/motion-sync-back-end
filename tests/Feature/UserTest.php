<?php

use App\Models\User;
use App\Models\achievements\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');

test ('Migrations', function () {
    Artisan::call('migrate:fresh');

    $this->assertTrue(Schema::hasTable('users'));
    $this->assertTrue(Schema::hasTable('friends'));
    $this->assertTrue(Schema::hasTable('leaderboards'));
    $this->assertTrue(Schema::hasTable('sessions'));
    $this->assertTrue(Schema::hasTable('themes'));
    $this->assertTrue(Schema::hasTable('work_outs'));
});

test('User can register', function () {
    Artisan::call('migrate:fresh');
    // Seed the join-motionsync achievement used in UserController::store
    $achievement = Achievement::create([
        'name' => 'Welcome to MotionSync',
        'description' => 'Join MotionSync and start your fitness journey.',
        'slug' => 'join-motionsync',
        'points' => 1,
    ]);

    $payload = User::factory()->make()->toArray();
    $payload['password'] = 'password123';
    $payload['password_confirmation'] = 'password123';
    $payload['date_of_birth'] = Carbon::parse($payload['date_of_birth'])->format('Y-m-d H:i:s');

    $response = $this->postJson('/api/user/store', $payload);

    $response->assertStatus(201)->assertJsonFragment(['display_name' => $payload['display_name']]);
    $this->assertDatabaseHas('users', ['display_name' => $payload['display_name']]);
    // Achievement for joining should be awarded
    $registered = User::where('display_name', $payload['display_name'])->first();
    $this->assertDatabaseHas('achievement_progress', [
        'user_id' => $registered->id,
        'achievement_id' => $achievement->id,
        'is_unlocked' => true,
    ]);
});

test('User cannot register with invalid data', function () {
    Artisan::call('migrate:fresh');

    $payload = [
        'name' => '',
        'display_name' => '',
        'email' => 'invalid-email',
        'password' => 'short',
        'password_confirmation' => 'short',
        'date_of_birth' => 'not-a-date',
    ];

    $response = $this->postJson('/api/user/store', $payload);

    $response->assertStatus(422)->assertJsonValidationErrors(['name', 'display_name', 'password', 'date_of_birth']);
});

test('User can authenticate and receive token', function () {
    Artisan::call('migrate:fresh');

    $password = 'password123';
    $user = User::factory()->create(['password' => Hash::make($password)]);

    $response = $this->postJson('/api/user/authenticate', [
        'display_name' => $user->display_name,
        'password' => $password,
    ]);

    $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'user']);
});

test('User cannot authenticate with wrong credentials', function () {
    Artisan::call('migrate:fresh');

    $password = 'password123';
    $user = User::factory()->create(['password' => Hash::make($password)]);

    $response = $this->postJson('/api/user/authenticate', [
        'display_name' => $user->display_name,
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)->assertJsonFragment(['message' => "Your name/password combination is not valid."]);
});

test('Private profile returns 403 for other users', function () {
    Artisan::call('migrate:fresh');

    $owner = User::factory()->create(['visibility' => false]);
    $other = User::factory()->create();

    Sanctum::actingAs($other);

    $response = $this->getJson("/api/user/{$owner->id}");

    $response->assertStatus(403);
});

test('Owner can view private profile', function () {
    Artisan::call('migrate:fresh');

    $owner = User::factory()->create(['visibility' => false]);

    Sanctum::actingAs($owner);

    $response = $this->getJson("/api/user/{$owner->id}");

    $response->assertStatus(200)->assertJsonFragment(['id' => $owner->id]);
});

test('Owner can update own profile', function () {
    Artisan::call('migrate:fresh');
    
    $owner = User::factory()->create(['visibility' => false]);

    Sanctum::actingAs($owner);

    $payload = ['visibility' => true];

    $response = $this->patchJson("/api/user/{$owner->id}", $payload);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['id' => $owner->id, 'visibility' => 1]);
});

test('User cannot update another user\'s profile', function () {
    Artisan::call('migrate:fresh');

    $owner = User::factory()->create(['visibility' => false]);
    $other = User::factory()->create();

    Sanctum::actingAs($other);

    $payload = ['visibility' => true];

    $response = $this->patchJson("/api/user/{$owner->id}", $payload);

    $response->assertStatus(403);
    $this->assertDatabaseHas('users', ['id' => $owner->id, 'visibility' => 0]);
});