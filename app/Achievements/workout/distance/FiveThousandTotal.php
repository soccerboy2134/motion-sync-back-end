<?php
declare(strict_types=1);

namespace App\Achievements\workout\distance;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\distance
 */
class FiveThousandTotal extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'FiveThousandTotal';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete a total of 5,000 meters in workouts.';
    public $slug = 'five-thousand-total';
    public $points = 5000;
}
