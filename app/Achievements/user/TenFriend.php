<?php
declare(strict_types=1);

namespace App\Achievements\user;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\user
 */
class TenFriend extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'TenFriend';

    /*
     * A small description for the achievement
     */
    public $description = 'Get 10 friends';
    public $slug = 'ten-friend';
    public $points = 10;
}
