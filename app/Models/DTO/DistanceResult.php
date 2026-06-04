<?php

namespace App\Models\DTO;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['m', 'km', 'time'])]
class DistanceResult extends Model
{
    // This class acts as a DTO for \App\Services\DistanceService.php's results.
}
