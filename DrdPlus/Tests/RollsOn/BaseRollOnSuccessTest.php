<?php
namespace DrdPlus\Tests\RollsOn;

use DrdPlus\RollsOn\BaseRollOnSuccess;
use DrdPlus\RollsOn\RollOnQuality;
use Granam\Boolean\BooleanInterface;
use Granam\Tests\Tools\TestWithMockery;

class BaseRollOnSuccessTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideDifficultyAndPropertyWithRoll
     * @param $difficulty
     * @param RollOnQuality $rollOnQuality
     * @param $shouldSuccess
     */
    public function I_can_use_it($difficulty, RollOnQuality $rollOnQuality, $shouldSuccess)
    {
        $baseRollOnSuccess = new BaseRollOnSuccess($difficulty, $rollOnQuality);

        self::assertInstanceOf(BooleanInterface::class, $baseRollOnSuccess);
        self::assertSame($difficulty, $baseRollOnSuccess->getDifficulty());
        self::assertSame($rollOnQuality, $baseRollOnSuccess->getRollOnQuality());

        if ($shouldSuccess) {
            self::assertTrue($baseRollOnSuccess->getValue());
            self::assertTrue($baseRollOnSuccess->isSuccessful());
            self::assertFalse($baseRollOnSuccess->isFailed());
            self::assertSame('success', (string)$baseRollOnSuccess);
        } else {
            self::assertFalse($baseRollOnSuccess->getValue());
            self::assertFalse($baseRollOnSuccess->isSuccessful());
            self::assertTrue($baseRollOnSuccess->isFailed());
            self::assertSame('fail', (string)$baseRollOnSuccess);
        }
    }

    public function provideDifficultyAndPropertyWithRoll()
    {
        return [
            [123, $this->createRollOnQuality(789), true],
            [999, $this->createRollOnQuality(998), false],
            [0, $this->createRollOnQuality(0), true],
            [1, $this->createRollOnQuality(0), false],
            [1, $this->createRollOnQuality(1), true],
        ];
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|RollOnQuality
     */
    private function createRollOnQuality($value)
    {
        $rollOnQuality = $this->mockery(RollOnQuality::class);
        $rollOnQuality->shouldReceive('getValue')
            ->andReturn($value);

        return $rollOnQuality;
    }
}
