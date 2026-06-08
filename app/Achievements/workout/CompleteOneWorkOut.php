<?php
declare(strict_types=1);

namespace App\Achievements\workout;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class CompleteOneWorkOut extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteOneWorkOut';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete your first workout!';

    public $slug = 'complete_one_workout';
}
