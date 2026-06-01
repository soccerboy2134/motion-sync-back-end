<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = json_decode(
            Storage::disk('local')->get('data.json'),
            true
        );
    
        foreach ($themes as $theme) {
            Theme::create([
                'name' => $theme['name'],
                'colorMain' => $theme['main'],
                'colorAccent' => $theme['accent'],
                'colorBackground' => $theme['background'],
                'colorButton' => $theme['button'],
                'colorText' => $theme['text'],
            ]);
        }
    }
}
