<?php

use App\Models\achievements\Achievement;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
})->purpose('Display an inspiring quote');

Artisan::command('achievement:migrate', function() {
    $name = 'testa';
    $achievementExists = Achievement::query()
        ->where('name', $name)
        ->first() ?: false;

    $this->comment($achievementExists);
})->purpose('Migrate Achievements from JSON to the database.');

// Artisan::command('inspire', function () {
    // $this->comment(Inspiring::quote());
    // $themes = json_decode(
        // Storage::disk('local')->get('data.json'),
        // true
    // );

    // $this->comment($themes);
// })->purpose('Display an inspiring quote');