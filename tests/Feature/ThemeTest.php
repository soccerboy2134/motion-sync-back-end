<?php

use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');

// test('Index returns themes for authenticated users', function () {
//     Artisan::call('migrate:fresh');

//     $user = User::factory()->create();

//     // create some themes
//     Theme::create([
//         'name' => 'Light',
//         'colorMain' => '#ffffff',
//         'colorAccent' => '#000000',
//         'colorBackground' => '#ffffff',
//         'colorButton' => '#eeeeee',
//         'colorText' => '#111111',
//     ]);
//     Theme::create([
//         'name' => 'Dark',
//         'colorMain' => '#000000',
//         'colorAccent' => '#ffffff',
//         'colorBackground' => '#111111',
//         'colorButton' => '#222222',
//         'colorText' => '#ffffff',
//     ]);

//     Sanctum::actingAs($user);

//     $response = $this->getJson('/api/theme/');

//     $response->assertStatus(200);
//     $response->assertJsonCount(2);
// });

// test('Store creates theme for admin and forbids non-admins', function () {
//     Artisan::call('migrate:fresh');

//     $admin = User::factory()->create(['role' => 'admin']);
//     $user = User::factory()->create(['role' => 'user']);

//     $payload = [
//         'name' => 'TestTheme',
//         'colorMain' => '#112233',
//         'colorAccent' => '#445566',
//         'colorBackground' => '#778899',
//         'colorButton' => '#aabbcc',
//         'colorText' => '#ddeeff',
//     ];

//     // non-admin should be forbidden
//     Sanctum::actingAs($user);
//     $resp1 = $this->postJson('/api/theme/store', $payload);
//     $resp1->assertStatus(403)->assertJsonFragment(['message' => 'BAD. your not allowed to be here.']);

//     // admin can create
//     Sanctum::actingAs($admin);
//     $resp2 = $this->postJson('/api/theme/store', $payload);
//     $resp2->assertStatus(201);
//     $this->assertDatabaseHas('themes', ['name' => 'TestTheme']);
// });

// test('Destroy removes theme for admin and forbids non-admins', function () {
//     Artisan::call('migrate:fresh');

//     $admin = User::factory()->create(['role' => 'admin']);
//     $user = User::factory()->create(['role' => 'user']);

//     $theme = Theme::create([
//         'name' => 'ToDelete',
//         'colorMain' => '#000000',
//         'colorAccent' => '#111111',
//         'colorBackground' => '#222222',
//         'colorButton' => '#333333',
//         'colorText' => '#444444',
//     ]);

//     // non-admin cannot delete
//     Sanctum::actingAs($user);
//     $resp1 = $this->deleteJson("/api/theme/{$theme->id}");
//     $resp1->assertStatus(403)->assertJsonFragment(['message' => 'BAD. your not allowed to be here.']);

//     // admin can delete
//     Sanctum::actingAs($admin);
//     $resp2 = $this->deleteJson("/api/theme/{$theme->id}");
//     $resp2->assertStatus(200)->assertJsonFragment(['message' => 'The theme was successfully deleted.']);
//     $this->assertDatabaseMissing('themes', ['id' => $theme->id]);
// });
test('Index returns themes for authenticated users', function () {
    Artisan::call('migrate:fresh');

    $user = User::factory()->create();

    Theme::create([
        'name' => 'Light',
        'bg' => '0xFFFFFFFF',
        'surface' => '0xFFFFFFFF',
        'primary' => '0xFF5C5BF7',
        'onPrimary' => '0xFFFFFFFF',
        'accent' => '0xFF5C5BF7',
        'text' => '0xFF0D0E1B',
        'muted' => '0xFF71717A',
        'border' => '0xFFEAEAEC',
    ]);

    Theme::create([
        'name' => 'Dark',
        'bg' => '0xFF0F0E17',
        'surface' => '0xFF1F1B2E',
        'primary' => '0xFFFF6B6B',
        'onPrimary' => '0xFF0F0E17',
        'accent' => '0xFFFFD93D',
        'text' => '0xFFFFFFFE',
        'muted' => '0xFFA7A9BE',
        'border' => '0xFF322C45',
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/theme/');

    $response->assertStatus(200);
    $response->assertJsonCount(2);
});

test('Store creates theme for admin and forbids non-admins', function () {
    Artisan::call('migrate:fresh');

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    $payload = [
        'name' => 'TestTheme',
        'bg' => '0xFFFFFFFF',
        'surface' => '0xFFFFFFFF',
        'primary' => '0xFF5C5BF7',
        'onPrimary' => '0xFFFFFFFF',
        'accent' => '0xFF5C5BF7',
        'text' => '0xFF0D0E1B',
        'muted' => '0xFF71717A',
        'border' => '0xFFEAEAEC',
    ];

    Sanctum::actingAs($user);
    $resp1 = $this->postJson('/api/theme/store', $payload);
    $resp1->assertStatus(403)->assertJsonFragment([
        'message' => 'BAD. your not allowed to be here.'
    ]);

    Sanctum::actingAs($admin);
    $resp2 = $this->postJson('/api/theme/store', $payload);
    $resp2->assertStatus(201);

    $this->assertDatabaseHas('themes', [
        'name' => 'TestTheme',
    ]);
});

test('Destroy removes theme for admin and forbids non-admins', function () {
    Artisan::call('migrate:fresh');

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    $theme = Theme::create([
        'name' => 'ToDelete',
        'bg' => '0xFF0F1E1A',
        'surface' => '0xFF1B2D27',
        'primary' => '0xFF52B788',
        'onPrimary' => '0xFF0F1E1A',
        'accent' => '0xFFF4A261',
        'text' => '0xFFF1FAEE',
        'muted' => '0xFF809B91',
        'border' => '0xFF26392F',
    ]);

    Sanctum::actingAs($user);
    $resp1 = $this->deleteJson("/api/theme/{$theme->id}");
    $resp1->assertStatus(403)->assertJsonFragment([
        'message' => 'BAD. your not allowed to be here.'
    ]);

    Sanctum::actingAs($admin);
    $resp2 = $this->deleteJson("/api/theme/{$theme->id}");

    $resp2->assertStatus(200)
        ->assertJsonFragment([
            'message' => 'The theme was successfully deleted.'
        ]);

    $this->assertDatabaseMissing('themes', [
        'id' => $theme->id,
    ]);
});