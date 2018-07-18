<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\QualityAndSuccess;

use Granam\Strict\Object\StrictObject;

class ComparisonOfRollsOnQuality extends StrictObject
{
    /**
     * @param RollOnQuality $lesserThan
     * @param RollOnQuality $thatOne
     * @return bool
     */
    public static function isLesser(RollOnQuality $lesserThan, RollOnQuality $thatOne): bool
    {
        return $lesserThan->getValue() < $thatOne->getValue();
    }

    /**
     * @param RollOnQuality $greaterThan
     * @param RollOnQuality $thatOne
     * @return bool
     */
    public static function isGreater(RollOnQuality $greaterThan, RollOnQuality $thatOne): bool
    {
        return $greaterThan->getValue() > $thatOne->getValue();
    }

    /**
     * @param RollOnQuality $equalTo
     * @param RollOnQuality $thatOne
     * @return bool
     */
    public static function isEqual(RollOnQuality $equalTo, RollOnQuality $thatOne): bool
    {
        return $equalTo->getValue() === $thatOne->getValue();
    }

    /**
     * @param RollOnQuality $compareThat
     * @param RollOnQuality $withThat
     * @return int
     */
    public static function compare(RollOnQuality $compareThat, RollOnQuality $withThat): int
    {
        return $compareThat->getValue() <=> $withThat->getValue();
    }
}