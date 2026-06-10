<?php

namespace Database\Seeders;

use App\Models\Skin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Storage;

class SkinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skins = json_decode(
            Storage::disk('local')->get('skins.json'),
            true
        );
    
        foreach ($skins as $skin) {
            Skin::create([
                'name' => $skin['name'],
                'location' => $skin['location'],
            ]);
        }
    }
}
