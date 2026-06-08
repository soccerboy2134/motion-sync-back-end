<?php
declare(strict_types=1);

namespace App\Achievements\workout\type;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\workout\type
 */
class CompleteTenSprints extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'CompleteTenSprints';

    /*
     * A small description for the achievement
     */
    public $description = 'Complete ten sprints';
    public $slug = 'complete-ten-sprints';
    public $points = 10;
}
