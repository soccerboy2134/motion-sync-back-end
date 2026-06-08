<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class FiveThousandMeterWorkOut extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'FiveThousandMeterWorkOut';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a 5,000 meter workout';
    public $slug = 'five-thousand-meter-workout';
    public $points = 5000;
}
