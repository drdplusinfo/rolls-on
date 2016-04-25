<?php
namespace DrdPlus\RollsOn;

use Granam\Strict\Object\StrictObject;

class ComparisonOfRollsOnSuccess extends StrictObject
{
    /**
     * @param RollOnSuccess $lesserThan
     * @param RollOnSuccess $thatOne
     * @return bool
     */
    public static function isLesser(RollOnSuccess $lesserThan, RollOnSuccess $thatOne)
    {
        return ComparisonOfRollsOnQuality::isLesser($lesserThan->getRollOnQuality(), $thatOne->getRollOnQuality());
    }

    /**
     * @param RollOnSuccess $greaterThan
     * @param RollOnSuccess $thatOne
     * @return bool
     */
    public static function isGreater(RollOnSuccess $greaterThan, RollOnSuccess $thatOne)
    {
        return ComparisonOfRollsOnQuality::isGreater($greaterThan->getRollOnQuality(), $thatOne->getRollOnQuality());
    }

    /**
     * @param RollOnSuccess $equalTo
     * @param RollOnSuccess $thatOne
     * @return bool
     */
    public static function isEqual(RollOnSuccess $equalTo, RollOnSuccess $thatOne)
    {
        return ComparisonOfRollsOnQuality::isEqual($equalTo->getRollOnQuality(), $thatOne->getRollOnQuality());
    }

    /**
     * @param RollOnSuccess $compareThat
     * @param RollOnSuccess $withThat
     * @return int
     */
    public static function compare(RollOnSuccess $compareThat, RollOnSuccess $withThat)
    {
        return ComparisonOfRollsOnQuality::compare($compareThat->getRollOnQuality(), $withThat->getRollOnQuality());
    }
}