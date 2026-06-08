<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteThousandRuns extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteThousandRuns';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete 1000 runs';
    public $slug = 'complete-thousand-runs';
    public $points = 1000;
}
