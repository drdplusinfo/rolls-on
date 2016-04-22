<?php
namespace DrdPlus\RollsOn;

use Drd\DiceRoll\Templates\Rollers\Roller2d6DrdPlus;
use Drd\DiceRoll\Templates\Rollers\SpecificRolls\Roll2d6DrdPlus;
use Granam\Tests\Tools\TestWithMockery;

class RollsOnTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_roll_on_fight()
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
    private function createRoller2d6DrdPlus($rollValue)
    {
        $roller2d6DrdPlus = $this->mockery(Roller2d6DrdPlus::class);
        $roller2d6DrdPlus->shouldReceive('roll')
            ->andReturn($roll2d6DrdPlus = $this->mockery(Roll2d6DrdPlus::class));
        $roll2d6DrdPlus->shouldReceive('getValue')
            ->andReturn($rollValue);

        return $roller2d6DrdPlus;
    }
}
