<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class TwoThousandTotal extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'TwoThousandTotal';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a total of 2,000 meters in workouts.';
    public $slug = 'two-thousand-total';
    public $points = 2000;
}
