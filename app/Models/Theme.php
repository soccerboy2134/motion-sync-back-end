<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name','bg','surface','primary','onPrimary','accent','text','muted','border',])]
class Theme extends Model
{
    use HasFactory;
}
