<?php
declare(strict_types=1);

namespace App\Achievements\workout;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class CompleteTenWorkOuts extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteTenWorkOuts';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete ten workouts!';

    public $slug = 'complete_ten_workouts';
    public $points = 10;
}
