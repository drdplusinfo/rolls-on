<?php
namespace DrdPlus\Tests\RollsOn\Traps;

use Drd\DiceRoll\Roll;
use DrdPlus\RollsOn\Traps\RollOnWillAgainstMalus;
use DrdPlus\RollsOn\Traps\RollOnWill;
use Granam\Tests\Tools\TestWithMockery;

class RollOnWillAgainstMalusTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideValuePreconditionsRollAndResult
     * @param $value
     * @param $expectedMalus
     */
    public function I_can_use_it($value, $expectedMalus)
    {
        $malusRollOnWillBecauseOfWounds = new RollOnWillAgainstMalus(
            $rollOnWill = $this->createRollOnWill($value)
        );
        self::assertSame($rollOnWill, $malusRollOnWillBecauseOfWounds->getRollOnWill());
        self::assertSame($rollOnWill, $malusRollOnWillBecauseOfWounds->getRollOnQuality());
        self::assertSame($expectedMalus, $malusRollOnWillBecauseOfWounds->getResult());
        self::assertSame($expectedMalus, $malusRollOnWillBecauseOfWounds->getMalusValue());
    }

    public function provideValuePreconditionsRollAndResult()
    {
        return [
            [4, -3],
            [5, -2],
            [9, -2],
            [10, -1],
            [14, -1],
            [15, 0],
        ];
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|RollOnWill
     */
    private function createRollOnWill($value)
    {
        $rollOnWill = $this->mockery(RollOnWill::class);
        $rollOnWill->shouldReceive('getValue')
            ->andReturn($value);
        $rollOnWill->shouldReceive('getPreconditionsSum')
            ->andReturn(123);
        $rollOnWill->shouldReceive('getRoll')
            ->andReturn($roll = $this->mockery(Roll::class));
        $roll->shouldReceive('getValue')
            ->andReturn(456);
        $roll->shouldReceive('getRolledNumbers')
            ->andReturn([789]);

        return $rollOnWill;
    }
}
