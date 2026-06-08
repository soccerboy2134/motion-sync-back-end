<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteThousandSprints extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteThousandSprints';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete 1000 sprints';
    public $slug = 'complete-thousand-sprints';
    public $points = 1000;
}
