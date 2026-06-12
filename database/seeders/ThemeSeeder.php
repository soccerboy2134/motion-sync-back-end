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
                'bg' => $theme['bg'],
                'surface' => $theme['surface'],
                'primary' => $theme['primary'],
                'onPrimary' => $theme['onPrimary'],
                'accent' => $theme['accent'],
                'text' => $theme['text'],
                'muted' => $theme['muted'],
                'border' => $theme['border'],
            ]);
        }
    }
}
