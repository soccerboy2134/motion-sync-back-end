<?php
declare(strict_types=1);

namespace App\Achievements\user;

use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements\user
 */
class BlockUser extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'BlockUser';

    /*
     * A small description for the achievement
     */
    public $description = 'Block a user';
    public $slug = 'block-user';
}
