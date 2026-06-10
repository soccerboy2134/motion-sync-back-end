<?php

namespace App\Models\achievements;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['badge_id', 'skin_id', 'name', 'description', 'slug', 'points'])]
class Achievement extends Model
{
    use HasFactory;

    public static function achievementExists(string $name) {
        return $achievementExists = Achievement::query()
            ->where('name', $name)
            ->first() ?: false;
    }

    public function chains() {
        return $this->belongsToMany(AchievementChainParent::class, 'achievement_chain_children', 'achievement_id', 'achievement_chain_parent_id');
    }

    public function progress() {
        return $this->hasMany(AchievementProgress::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'achievement_progress', 'achievement_id', 'user_id')->withPivot('is_unlocked');
    }

    public function userProgress()
    {
        return $this->hasOne(AchievementProgress::class)
            ->where('user_id', Auth::id());
    }

    public static function getAchievementsWithProgress()
    {
        // also take Badge.php into this method and return it alognside Achievement.
        return self::with('userProgress')->get()->map(function ($achievement) {
            $achievement->badge = Badge::find($achievement->badge_id);
            return $achievement;
        });
        // return self::with('userProgress')->get();
    }
}
