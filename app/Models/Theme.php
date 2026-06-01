<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name', 'colorMain', 'colorAccent', 'colorBackground', 'colorButton', 'colorText'])]
class Theme extends Model
{
    use HasFactory;
}
