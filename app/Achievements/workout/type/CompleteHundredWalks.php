<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteHundredWalks extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteHundredWalks';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete 100 walks';
    public $slug = 'complete-hundred-walks';
    public $points = 100;
}
