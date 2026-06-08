<?php
declare(strict_types=1);

namespace App\Achievements\workout;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class CompleteHundredWorkOuts extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteHundredWorkOuts';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete one hundred workouts!';
    public $slug = 'complete_hundred_workouts';
    public $points = 100;
}
