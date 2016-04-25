<?php
namespace DrdPlus\RollsOn;

use Granam\Strict\Object\StrictObject;

class ComparisonOfRollsOnQuality extends StrictObject
{
    /**
     * @param RollOnQuality $lesserThan
     * @param RollOnQuality $thatOne
     * @return bool
     */
    public static function isLesser(RollOnQuality $lesserThan, RollOnQuality $thatOne)
    {
        return $lesserThan->getValue() < $thatOne->getValue();
    }

    /**
     * @param RollOnQuality $greaterThan
     * @param RollOnQuality $thatOne
     * @return bool
     */
    public static function isGreater(RollOnQuality $greaterThan, RollOnQuality $thatOne)
    {
        return $greaterThan->getValue() < $thatOne->getValue();
    }

    /**
     * @param RollOnQuality $equalTo
     * @param RollOnQuality $thatOne
     * @return bool
     */
    public static function isEqual(RollOnQuality $equalTo, RollOnQuality $thatOne)
    {
        return $equalTo->getValue() === $thatOne->getValue();
    }

    /**
     * @param RollOnQuality $compareThat
     * @param RollOnQuality $withThat
     * @return int
     */
    public static function compare(RollOnQuality $compareThat, RollOnQuality $withThat)
    {
        if ($compareThat->getValue() < $withThat->getValue()) {
            return -1;
        }
        if ($compareThat->getValue() > $withThat->getValue()) {
            return 1;
        }

        return 0;
    }
}