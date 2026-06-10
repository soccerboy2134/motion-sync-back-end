<?php

namespace Database\Seeders;

use App\Models\achievements\Achievement;
use App\Models\achievements\AchievementChainChild;
use App\Models\achievements\AchievementChainParent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = json_decode(
            Storage::disk('local')->get('achievements.json'),
            true
        );

        foreach ($achievements as $achievement) {
            // Step 1: Does this achievement already exist?
            // Step 2: Have we seen this chain before?
            // If yes, ignore. If not, create chain. 
            // Step 3: Create Achievement
            // Step 4: Add to Chain

            if (Achievement::achievementExists($achievement['Name'])) continue;
            $currentChain = null;
            if (array_key_exists('chain', $achievement)) {
                $name = $achievement['chain'];
                
                $chain = AchievementChainParent::chainExists($name);
                if ($chain == null) {
                    $chain = AchievementChainParent::create([
                        'name' => $name, 
                    ]);

                    $currentChain = $chain;
                }
                else {
                    $currentChain = $chain;
                }
            }

            $achievement = Achievement::create([
                'skin_id' => $achievement['unlocks'],
                'badge_id' => $achievement['badge'],
                'name' => $achievement['Name'],
                'description' => $achievement['Description'],
                'slug' => $achievement['Slug'],
                'points' => $achievement['points'],
            ]);

            if ($currentChain != null) {
                AchievementChainChild::create([
                    'achievement_chain_parent_id' => $currentChain->id,
                    'achievement_id' => $achievement->id,
                ]);
            }
        }
    }
}
