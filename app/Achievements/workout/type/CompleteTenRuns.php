<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteTenRuns extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteTenRuns';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete ten runs';
    public $slug = 'complete-ten-runs';
    public $points = 10;
}
