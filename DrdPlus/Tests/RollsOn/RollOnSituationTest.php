<?php
namespace DrdPlus\Tests\RollsOn;

use Drd\DiceRoll\Templates\Rollers\SpecificRolls\Roll2d6DrdPlus;
use Granam\Tests\Tools\TestWithMockery;

abstract class RollOnSituationTest extends TestWithMockery
{
    /**
     * @test
     */
    abstract public function I_can_use_it();

    /**
     * @param $value
     * @return \Mockery\MockInterface|Roll2d6DrdPlus
     */
    protected function createRoll2d6DrdPlus($value)
    {
        $roll2d6DrdPlus = $this->mockery(Roll2d6DrdPlus::class);
        $roll2d6DrdPlus->shouldReceive('getValue')
            ->andReturn($value);

        return $roll2d6DrdPlus;
    }
}
