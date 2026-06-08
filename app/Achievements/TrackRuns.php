<?php
declare(strict_types=1);

namespace App\Achievements;

use App\Achievements\workout\type\CompleteHundredRuns;
use App\Achievements\workout\type\CompleteTenRuns;
use App\Achievements\workout\type\CompleteThousandRuns;
use Assada\Achievements\AchievementChain;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class TrackRuns extends AchievementChain
{
    /*
     * Returns a list of instances of Achievements
     */
    public function chain(): array
    {
        return [
            new CompleteTenRuns(),
            new CompleteHundredRuns(),
            new CompleteThousandRuns(),
        ];
    }
}
