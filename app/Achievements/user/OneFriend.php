<?php
declare(strict_types=1);

namespace App\Achievements\user;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\user
 */
class OneFriend extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'OneFriend';

    /*
     * A small description for the achievement
     */
    public $description = 'Get 1 friend';
    public $slug = 'one-friend';
    public $points = 1;
}
