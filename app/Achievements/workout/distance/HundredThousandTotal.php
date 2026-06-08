<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class HundredThousandTotal extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'HundredThousandTotal';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a total of 100,000 meters in workouts.';
    public $slug = 'hundred-thousand-total';
    public $points = 100000;
}
