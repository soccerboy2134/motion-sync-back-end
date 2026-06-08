<?php
declare(strict_types=1);

namespace App\Achievements\user;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class JoinMotionSync extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'JoinMotionSync';

    /*
     * A small description for the achievement
     */
    public $description = 'Join MotionSync and start your fitness journey!';

    public $slug = 'join_motion_sync';
}
