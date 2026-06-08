<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteThousandWalks extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteThousandWalks';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete 1000 walks';
    public $slug = 'complete-thousand-walks';
    public $points = 1000;
}
