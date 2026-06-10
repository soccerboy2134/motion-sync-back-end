<?php

namespace App\Models\achievements;

use App\Models\UnlockedSkin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

#[Fillable(['user_id', 'achievement_id', 'points', 'isUnlocked'])]
class AchievementProgress extends Model
{
    use HasFactory;

    public static function progress(string $slug, int $points, $userId = null) {
        if ($userId == null) $userId = Auth::user()->id;
        $achievement = Achievement::where('slug', $slug)->first();
        if (!$achievement) return;

        $progress = self::where('user_id', Auth::id())->where('achievement_id', $achievement->id)->first();
        if (!$progress) {
            $progress = new self();
            $progress->user_id = $userId;
            $progress->achievement_id = $achievement->id;
            $progress->points = $points;
            $progress->is_unlocked = false;
        } else {
            // if points > achievement points, set to achievement points (prevents overprogressing). do not add points
            if ($progress->points >= $achievement->points) return;
            $progress->points += $points;
            if ($progress->points > $achievement->points) {
                $progress->points = $achievement->points;
            }
        }
        // Check if the achievement is unlocked
        if (!$progress->is_unlocked && $progress->points >= $achievement->points) {
            $progress->is_unlocked = true;

            // Unlock associated skin using UnlockedSkin Model if it hasn't been unlocked already
            if ($achievement->skin_id && !UnlockedSkin::where('user_id', $userId)->where('skin_id', $achievement->skin_id)->exists()) {
                 UnlockedSkin::create([
                    'user_id' => $userId,
                    'skin_id' => $achievement->skin_id
                ]);
            }
        }

        $progress->save();
    }

    public static function progressChain(string $chain, int $points) {
        // Use AchievementChainParent to get the chain ID, then AchievementChainChild to get all achievements
        $chainParent = AchievementChainParent::where('name', $chain)->first();
        if (!$chainParent) return;

        $chainChildren = AchievementChainChild::where('achievement_chain_parent_id', $chainParent->id)->get();
        foreach ($chainChildren as $child) {
            self::progress($child->achievement->slug, $points);
        }
    }

    public static function getCompletedAchievements() {
        return self::where('user_id', Auth::id())->where('is_unlocked', true)->get();
    }

    public static function getInProgressAchievements() {
        return self::where('user_id', Auth::id())->where('is_unlocked', false)->get();
    }

    public static function getAllProgression() {
        $completed = self::getCompletedAchievements();
        $inProgress = self::getInProgressAchievements();
        $all = $completed->merge($inProgress);
        return $all->sortBy('achievement_id')->values();
    }
    
    public function achievements() {
        return $this->belongsTo(Achievement::class, 'achievement_id');
    }
}
