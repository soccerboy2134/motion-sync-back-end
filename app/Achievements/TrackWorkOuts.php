<?php
declare(strict_types=1);

namespace App\Achievements;

use App\Achievements\workout\CompleteHundredWorkOuts;
use App\Achievements\workout\CompleteOneWorkOut;
use App\Achievements\workout\CompleteTenWorkOuts;
use App\Achievements\workout\CompleteThousandWorkOuts;
use App\Achievements\workout\type\CompleteTenRuns;
use Assada\Achievements\AchievementChain;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class TrackWorkOuts extends AchievementChain
{
    /*
     * Returns a list of instances of Achievements
     */
    public function chain(): array
    {
        return [
            new CompleteOneWorkOut(),
            new CompleteTenWorkOuts(),
            new CompleteHundredWorkOuts(),
            new CompleteThousandWorkOuts(),
        ];
    }
}
