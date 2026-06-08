<?php
declare(strict_types=1);

namespace App\Achievements\leaderboard;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\leaderboard
 */
class EnteredTopOne extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'EnteredTopOne';

    /*
     * A small description for the achievement
     */
    public $description = 'Reach #1 on the global leaderboard.';

    public $slug = 'leaderboard-global-one';
}
