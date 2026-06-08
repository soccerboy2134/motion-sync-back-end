<?php
declare(strict_types=1);

namespace App\Achievements;

use App\Achievements\workout\type\CompleteHundredSprints;
use App\Achievements\workout\type\CompleteTenSprints;
use App\Achievements\workout\type\CompleteThousandSprints;
use Assada\Achievements\AchievementChain;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class TrackSprints extends AchievementChain
{
    /*
     * Returns a list of instances of Achievements
     */
    public function chain(): array
    {
        return [
            new CompleteTenSprints(),
            new CompleteHundredSprints(),
            new CompleteThousandSprints(),
        ];
    }
}
