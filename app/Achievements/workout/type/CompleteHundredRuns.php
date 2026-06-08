<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteHundredRuns extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteHundredRuns';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete 100 runs';
    public $slug = 'complete-hundred-runs';
    public $points = 100;
}
