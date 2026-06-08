<?php
declare(strict_types=1);

namespace App\Achievements\workout;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class CompleteThousandWorkOuts extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteThousandWorkOuts';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete one thousand workouts!';
    public $slug = 'complete_thousand_workouts';
    public $points = 1000;
}
