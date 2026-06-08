<?php
declare(strict_types=1);

namespace App\Achievements;

use App\Achievements\workout\distance\FiveThousandTotal;
use App\Achievements\workout\distance\HundredThousandTotal;
use App\Achievements\workout\distance\SevenThousandTotal;
use App\Achievements\workout\distance\TenThousandTotal;
use App\Achievements\workout\distance\TwoThousandTotal;
use Assada\Achievements\AchievementChain;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class TrackFullWorkOutLength extends AchievementChain
{
    /*
     * Returns a list of instances of Achievements
     */
    public function chain(): array
    {
        return [
            new TwoThousandTotal(),
            new FiveThousandTotal(),
            new SevenThousandTotal(),
            new TenThousandTotal(),
            new HundredThousandTotal(),
        ];
    }
}
