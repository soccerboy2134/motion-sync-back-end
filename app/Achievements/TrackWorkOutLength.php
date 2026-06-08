<?php
declare(strict_types=1);

namespace App\Achievements;

use App\Achievements\workout\distance\FiveThousandMeterWorkOut;
use App\Achievements\workout\distance\OneThousandMeterWorkOut;
use App\Achievements\workout\distance\TenThousandMeterWorkOut;
use App\Achievements\workout\distance\TwoAndAHalfThousandMeterWorkOut;
use App\Achievements\workout\distance\TwoThousandMeterWorkOut;
use Assada\Achievements\AchievementChain;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class TrackWorkOutLength extends AchievementChain
{
    /*
     * Returns a list of instances of Achievements
     */
    public function chain(): array
    {
        return [
            new OneThousandMeterWorkOut(),
            new TwoThousandMeterWorkOut(),
            new TwoAndAHalfThousandMeterWorkOut(),
            new FiveThousandMeterWorkOut(),
            new TenThousandMeterWorkOut(),
        ];
    }
}
