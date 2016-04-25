<?php
namespace DrdPlus\RollsOn;

use Drd\DiceRoll\Roll;
use Drd\DiceRoll\Roller;
use Drd\DiceRoll\Templates\Rollers\Roller2d6DrdPlus;
use Drd\DiceRoll\Templates\Rollers\SpecificRolls\Roll2d6DrdPlus;
use Granam\Tests\Tools\TestWithMockery;

class RollsOnTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_make_roll_on_fight()
    {
        $rollsOn = new RollsOn($roller = $this->createRoller2d6DrdPlus($rollValue = 123));
        $rollOnFight = $rollsOn->makeRollOnFight($fightNumber = 55667788);
        self::assertInstanceOf(RollOnFight::class, $rollOnFight);
        self::assertSame($fightNumber, $rollOnFight->getFightNumber());
        self::assertSame($fightNumber + $rollValue, $rollOnFight->getValue());
    }

    /**
     * @param $rollValue
     * @return \Mockery\MockInterface|Roller2d6DrdPlus
     */
    private function createRoller2d6DrdPlus($rollValue = false)
    {
        $roller2d6DrdPlus = $this->mockery(Roller2d6DrdPlus::class);
        $roller2d6DrdPlus->shouldReceive('roll')
            ->andReturn($roll2d6DrdPlus = $this->mockery(Roll2d6DrdPlus::class));
        if ($rollValue !== false) {
            $roll2d6DrdPlus->shouldReceive('getValue')
                ->andReturn($rollValue);
        }

        return $roller2d6DrdPlus;
    }

    /**
     * @test
     */
    public function I_can_make_roll_on_quality()
    {
        $rollsOn = new RollsOn($this->createRoller2d6DrdPlus());

        $rollOnQuality = $rollsOn->makeRollOnQuality(
            $preconditionsSum = 123,
            $roller = $this->createRoller($roll = $this->createRoll($rollValue = 456))
        );
        self::assertSame($preconditionsSum, $rollOnQuality->getPreconditionsSum());
        self::assertSame($roll, $rollOnQuality->getRoll());
        $expectedResult = $preconditionsSum + $rollValue;
        self::assertSame($expectedResult, $rollOnQuality->getValue());
        self::assertSame((string)$expectedResult, (string)$rollOnQuality->getValue());
    }

    /**
     * @test
     */
    public function I_can_make_basic_roll_on_success()
    {
        $rollsOn = new RollsOn($this->createRoller2d6DrdPlus());

        $basicRollOnSuccess = $rollsOn->makeBasicRollOnSuccess(
            $difficulty = 123,
            $preconditionsSum = 456,
            $this->createRoller($roll = $this->createRoll())
        );
        self::assertInstanceOf(BasicRollOnSuccess::class, $basicRollOnSuccess);
        self::assertSame($difficulty, $basicRollOnSuccess->getDifficulty());
        self::assertInstanceOf(RollOnQuality::class, $rollOnQuality = $basicRollOnSuccess->getRollOnQuality());

        self::assertSame($roll, $rollOnQuality->getRoll());
    }

    /**
     * @param $roll
     * @return \Mockery\MockInterface|Roller
     */
    private function createRoller($roll)
    {
        $roller = $this->mockery(Roller::class);
        $roller->shouldReceive('roll')
            ->andReturn($roll);

        return $roller;
    }

    /**
     * @param $rollValue
     * @return \Mockery\MockInterface|Roll
     */
    private function createRoll($rollValue = false)
    {
        $roll = $this->mockery(Roll::class);
        if ($rollValue !== false) {
            $roll->shouldReceive('getValue')
                ->andReturn($rollValue);
        }

        return $roll;
    }
}
