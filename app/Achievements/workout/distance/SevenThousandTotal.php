<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class SevenThousandTotal extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'SevenThousandTotal';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a total of 7,000 meters in workouts.';
    public $slug = 'seven-thousand-total';
    public $points = 7000;
}
