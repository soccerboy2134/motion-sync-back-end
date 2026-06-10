<?php

namespace App\Models;

use App\Models\achievements\Achievement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'location'])]
class Badge extends Model
{
    public function achievement() {
        return $this->belongsTo(Achievement::class);
    }
}
