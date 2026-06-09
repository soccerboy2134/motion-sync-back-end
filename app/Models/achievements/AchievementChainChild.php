<?php

namespace App\Models\achievements;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['achievement_chain_parent_id', 'achievement_id'])]
class AchievementChainChild extends Model
{
    use HasFactory;

    public function parent() {
        return $this->belongsTo(AchievementChainParent::class, 'achievement_chain_parent_id');
    }

    public function achievement() {
        return $this->belongsTo(Achievement::class, 'achievement_id');
    }
}
