<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'skin_id'])]
class UnlockedSkin extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function skin() {
        return $this->belongsTo(Skin::class);
    }
}
