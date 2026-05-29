<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['colorMain', 'colorAccent', 'colorBackground', 'colorButton', 'colorText'])]
class Theme extends Model
{
    //
}
