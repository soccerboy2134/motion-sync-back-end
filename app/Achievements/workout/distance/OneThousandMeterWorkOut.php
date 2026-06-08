<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class OneThousandMeterWorkOut extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'OneThousandMeterWorkOut';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a workout of 1000 meters';
    public $slug = 'one-thousand-meter-workout';
    public $points = 1000;
}
