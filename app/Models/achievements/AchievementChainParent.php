<?php

namespace App\Models\achievements;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name'])]
class AchievementChainParent extends Model
{
    use HasFactory;

    public static function chainExists(string $name) {
        return $achievementExists = AchievementChainParent::query()
            ->where('name', $name)
            ->first() ?: false;
    }

    public function children() {
        return $this->hasMany(AchievementChainChild::class, 'achievement_chain_parent_id');
    }
}
