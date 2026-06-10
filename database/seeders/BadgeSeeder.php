<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $badges = json_decode(
            Storage::disk('local')->get('badges.json'),
            true
        );
    
        foreach ($badges as $badge) {
            Badge::create([
                'name' => $badge['name'],
                'location' => $badge['location'],
            ]);
        }
    }
}
