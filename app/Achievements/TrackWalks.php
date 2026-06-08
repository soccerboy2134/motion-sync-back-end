<?php
declare(strict_types=1);

namespace App\Achievements;

use App\Achievements\workout\type\CompleteHundredWalks;
use App\Achievements\workout\type\CompleteTenWalks;
use App\Achievements\workout\type\CompleteThousandWalks;
use Assada\Achievements\AchievementChain;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class TrackWalks extends AchievementChain
{
    /*
     * Returns a list of instances of Achievements
     */
    public function chain(): array
    {
        return [
            new CompleteTenWalks(),
            new CompleteHundredWalks(),
            new CompleteThousandWalks(),
        ];
    }
}
