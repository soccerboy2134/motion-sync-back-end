<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteTenWalks extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteTenWalks';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete 10 walks';
    public $slug = 'complete-ten-walks';
    public $points = 10;
}
