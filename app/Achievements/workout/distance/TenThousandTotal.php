<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class TenThousandTotal extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'TenThousandTotal';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a total of 10,000 meters in workouts.';
    public $slug = 'ten-thousand-total';
    public $points = 10000;
}
