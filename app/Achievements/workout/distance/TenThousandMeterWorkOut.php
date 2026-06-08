<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class TenThousandMeterWorkOut extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'TenThousandMeterWorkOut';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a 10,000 meter workout';
    public $slug = 'ten-thousand-meter-workout';
    public $points = 10000;
}
