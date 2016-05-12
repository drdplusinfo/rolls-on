<?php
namespace DrdPlus\RollsOn\Traps;

use Drd\DiceRoll\Templates\Rollers\Roller2d6DrdPlus;
use DrdPlus\Properties\Base\Will;

class RollOnWillTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $rollOnWill = new RollOnWill(
            $will = Will::getIt($willValue = 123),
            $roll = Roller2d6DrdPlus::getIt()->roll()
        );
        self::assertSame($will, $rollOnWill->getWill());
        self::assertSame($willValue, $rollOnWill->getPreconditionsSum());
        self::assertSame($roll, $rollOnWill->getRoll());
        $resultValue = $willValue + $roll->getValue();
        self::assertSame($resultValue, $rollOnWill->getValue());
        self::assertSame((string)$resultValue, (string)$rollOnWill);
    }
}
