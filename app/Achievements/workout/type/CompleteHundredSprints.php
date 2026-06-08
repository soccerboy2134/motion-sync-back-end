<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteHundredSprints extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteHundredSprints';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete 100 sprints';
    public $slug = 'complete-hundred-sprints';
    public $points = 100;
}
