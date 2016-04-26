<?php
namespace DrdPlus\Tests\RollsOn;

use Drd\DiceRoll\Roll;
use DrdPlus\RollsOn\MalusRollOnWillBecauseOfWounds;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use Granam\Tests\Tools\TestWithMockery;

class MalusRollOnWillBecauseOfWoundsTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideValuePreconditionsRollAndResult
     * @param $value
     * @param $expectedCode
     * @param $expectedMalus
     */
    public function I_can_use_it($value, $expectedCode, $expectedMalus)
    {
        $malusRollOnWillBecauseOfWounds = new MalusRollOnWillBecauseOfWounds(
            $rollOnWill = $this->createRollOnQuality($value)
        );
        self::assertSame($rollOnWill, $malusRollOnWillBecauseOfWounds->getRollOnWill());
        self::assertSame($rollOnWill, $malusRollOnWillBecauseOfWounds->getRollOnQuality());
        self::assertSame($expectedCode, $malusRollOnWillBecauseOfWounds->getResultCode());
        self::assertSame($expectedMalus, $malusRollOnWillBecauseOfWounds->getValue());
    }

    public function provideValuePreconditionsRollAndResult()
    {
        return [
            [4, 'highest_malus', -3],
            [5, 'medium_malus', -2],
            [9, 'medium_malus', -2],
            [10, 'lowest_malus', -1],
            [14, 'lowest_malus', -1],
            [15, 'without_malus', 0],
        ];
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|RollOnQuality
     */
    private function createRollOnQuality($value)
    {
        $rollOnQuality = $this->mockery(RollOnQuality::class);
        $rollOnQuality->shouldReceive('getValue')
            ->andReturn($value);
        $rollOnQuality->shouldReceive('getPreconditionsSum')
            ->andReturn(123);
        $rollOnQuality->shouldReceive('getRoll')
            ->andReturn($roll = $this->mockery(Roll::class));
        $roll->shouldReceive('getValue')
            ->andReturn(456);
        $roll->shouldReceive('getRolledNumbers')
            ->andReturn([789]);

        return $rollOnQuality;
    }

    /**
     * @test
     * @expectedException \DrdPlus\RollsOn\Exceptions\UnknownRollOnWillResultCode
     * @expectedExceptionMessageRegExp ~strange magic~
     */
    public function I_can_not_use_internally_custom_result_code()
    {
        $broken = new BrokenMalusRollOnWillBecauseOfWounds($this->createRollOnQuality(123));
        $broken->getValue();
    }
}

/** inner */
class BrokenMalusRollOnWillBecauseOfWounds extends MalusRollOnWillBecauseOfWounds
{
    public function getResultCode()
    {
        return 'strange magic';
    }
}