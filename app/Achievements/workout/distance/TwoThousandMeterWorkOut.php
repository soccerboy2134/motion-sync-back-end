<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class TwoThousandMeterWorkOut extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'TwoThousandMeterWorkOut';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a 2000 meter workout';
    public $slug = 'two-thousand-meter-workout';
    public $points = 2000;
}
