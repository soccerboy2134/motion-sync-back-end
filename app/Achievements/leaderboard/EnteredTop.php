<?php
declare(strict_types=1);

namespace App\Achievements\leaderboard;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\leaderboard
 */
class EnteredTop extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'EnteredTop';

    /*
     * A small description for the achievement
     */
    public $description = 'Enter the leaderboard for the first time.';

    public $slug = 'leaderboard-entered-top';
}
