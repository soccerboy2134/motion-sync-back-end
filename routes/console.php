<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Faker\Generator;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
})->purpose('Display an inspiring quote');

Artisan::command('faker', function() {
    // $gender = 'x';

    // $this->comment(fake()->firstName($gender));
    // $this->comment(fake()->firstName($gender));
    // $this->comment(fake()->firstName($gender));
    // $this->comment(fake()->firstName($gender));
    // $this->comment(fake()->firstName($gender));

    $dob = fake()->dateTimeBetween('-70 years', '-15 years');
    if (date('Y') -  $dob->format('Y') > 18) {
        $this->comment($dob->format('Y') . 'a');
    }
    else {
        $this->comment($dob->format('Y'));
    }
    // $this->comment($dob->format('Y-m-d'));
});

Artisan::command('size', function() {
    $coords = ['1', '2', '3'];

    for ($i = 0; $i < sizeof($coords); $i++) {
        if ($i = sizeof($coords) - 1 && !array_key_exists($i + 1, $coords)) {
            // Array ended; we're at the last key. I don't know if we can calculate this using this method..
            // for now we just say it's finished.
        }
    }

    // $this->comment(sizeof($test));
});