<?php
declare(strict_types=1);

namespace App\Achievements\user;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\user
 */
class HundredFriend extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'HundredFriend';

    /*
     * A small description for the achievement
     */
    public $description = 'Get 100 friends';
    public $slug = 'hundred-friend';
    public $points = 100;
}
