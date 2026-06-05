<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

test('Index groups friendships correctly', function () {
    Artisan::call('migrate:fresh');

    $user = User::factory()->create();
    $other = User::factory()->create();
    $third = User::factory()->create();
    // pending where user is sender
    Friend::create([
        'sender' => $user->id,
        'receiver' => $other->id,
        'status' => 'pending',
    ]);
    // friend where user is receiver
    Friend::create([
        'sender' => $third->id,
        'receiver' => $user->id,
        'status' => 'friend',
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/friend');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'pending',
        'friend',
    ]);
});

test('Store creates request and blocks duplicates', function () {
    Artisan::call('migrate:fresh');

    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    Sanctum::actingAs($sender);
    // first request should create
    $resp1 = $this->postJson("/api/friend/{$receiver->id}");
    $resp1->assertStatus(201)->assertJsonFragment(['status' => 'pending']);
    // second request should be rejected (duplicate)
    $resp2 = $this->postJson("/api/friend/{$receiver->id}");
    $resp2->assertStatus(400)->assertJsonFragment([
        'message' => 'You already have a relationship with this user.',
    ]);
});

test('Update accepts unfriend block and unblock', function () {
    Artisan::call('migrate:fresh');

    $alice = User::factory()->create();
    $bob = User::factory()->create();
    // Bob sends request to Alice
    Friend::create([
        'sender' => $bob->id,
        'receiver' => $alice->id,
        'status' => 'pending',
    ]);
    // Alice accepts
    Sanctum::actingAs($alice);
    $accept = $this->putJson('/api/friend', [
        'user_id' => $bob->id,
        'status' => 'accepted',
    ]);
    $accept->assertStatus(200)->assertJsonFragment([
        'message' => 'Friend request accepted.',
    ]);
    $this->assertDatabaseHas('friends', [
        'sender' => $bob->id,
        'receiver' => $alice->id,
        'status' => 'friend',
    ]);
    // Alice unfriends Bob
    $unfriend = $this->putJson('/api/friend', [
        'user_id' => $bob->id,
        'status' => 'unfriend',
    ]);
    $unfriend->assertStatus(200)->assertJsonFragment([
        'message' => 'User unfriended.',
    ]);
    $this->assertDatabaseMissing('friends', [
        'sender' => $bob->id,
        'receiver' => $alice->id,
    ]);
    // Alice blocks Bob
    $block = $this->putJson('/api/friend', [
        'user_id' => $bob->id,
        'status' => 'block',
    ]);
    $block->assertStatus(200)->assertJsonFragment([
        'message' => 'User blocked.',
    ]);
    $this->assertDatabaseHas('friends', [
        'sender' => $alice->id,
        'receiver' => $bob->id,
        'status' => 'blocked',
    ]);
    // Alice unblocks Bob
    $unblock = $this->putJson('/api/friend', [
        'user_id' => $bob->id,
        'status' => 'unblock',
    ]);
    $unblock->assertStatus(200)->assertJsonFragment([
        'message' => 'User unblocked.',
    ]);
    $this->assertDatabaseMissing('friends', [
        'sender' => $alice->id,
        'receiver' => $bob->id,
    ]);
});
