<?php
declare(strict_types=1);

namespace App\Achievements;

use Assada\Achievements\AchievementChain;

/**
 * Class Registered
 *
 * @package App\Achievements\chains
 */
class TrackFriends extends AchievementChain
{
    /*
     * Returns a list of instances of Achievements
     */
    public function chain(): array
    {
        return [
            new user\OneFriend(),
            new user\TenFriend(),
            new user\HundredFriend(),
        ];
    }
}
