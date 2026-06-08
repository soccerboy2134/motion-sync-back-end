<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class TwoAndAHalfThousandMeterWorkOut extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'TwoAndAHalfThousandMeterWorkOut';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a workout of 2500 meters';
    public $slug = 'two-and-a-half-thousand-meter-workout';
    public $points = 2500;
}
