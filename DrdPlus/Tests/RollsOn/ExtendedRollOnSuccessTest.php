<?php
namespace DrdPlus\Tests\RollsOn;

use Drd\DiceRoll\Roll;
use DrdPlus\RollsOn\ExtendedRollOnSuccess;
use DrdPlus\RollsOn\RollOnQuality;
use DrdPlus\RollsOn\SimpleRollOnSuccess;
use Granam\Tests\Tools\TestWithMockery;

class ExtendedRollOnSuccessTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideSimpleRollsOnSuccessAndResult
     * @param RollOnQuality $expectedRollOnQuality
     * @param array $simpleRollsOnSuccess
     * @param string $expectedResultCode
     * @param bool $expectingSuccess
     */
    public function I_can_use_it(RollOnQuality $expectedRollOnQuality, array $simpleRollsOnSuccess, $expectedResultCode, $expectingSuccess)
    {
        $extendedRollOnSuccessReflection = new \ReflectionClass(ExtendedRollOnSuccess::class);
        $extendedRollOnSuccess = $extendedRollOnSuccessReflection->newInstanceArgs($simpleRollsOnSuccess);

        self::assertSame($expectedRollOnQuality, $extendedRollOnSuccess->getRollOnQuality());
        self::assertSame($expectedResultCode, $extendedRollOnSuccess->getResultCode());
        self::assertSame($expectedResultCode, (string)$extendedRollOnSuccess);
        if ($expectingSuccess) {
            self::assertFalse($extendedRollOnSuccess->isFailed());
            self::assertTrue($extendedRollOnSuccess->isSuccessful());
        } else {
            self::assertTrue($extendedRollOnSuccess->isFailed());
            self::assertFalse($extendedRollOnSuccess->isSuccessful());
        }
    }

    public function provideSimpleRollsOnSuccessAndResult()
    {
        return [
            [ // from more than three simple rolls on success
                $rollOnQuality = $this->createRollOnQuality(1 /* roll value */),
                [
                    $this->createSimpleRollOnSuccess(1 /* difficulty */, $rollOnQuality, true /* success */, $expectedResultCode = 'success'),
                    $this->createSimpleRollOnSuccess(2 /* difficulty */, $rollOnQuality),
                    $this->createSimpleRollOnSuccess(3 /* difficulty */, $rollOnQuality),
                    $this->createSimpleRollOnSuccess(5 /* difficulty */, $rollOnQuality),
                ],
                $expectedResultCode,
                true /* expecting success */
            ],
            [ // from single simple roll on success
                $rollOnQuality = $this->createRollOnQuality(5 /* roll value */),
                [
                    $this->createSimpleRollOnSuccess(9 /* difficulty */, $rollOnQuality),
                ],
                'fail',
                false /* expecting failure */
            ],
        ];
    }

    /**
     * @param $difficulty
     * @param RollOnQuality $rollOnQuality
     * @param $isSuccessful
     * @param $resultCode
     * @return \Mockery\MockInterface|SimpleRollOnSuccess
     */
    private function createSimpleRollOnSuccess($difficulty, RollOnQuality $rollOnQuality, $isSuccessful = false, $resultCode = 'foo')
    {
        $simpleRollOnSuccess = $this->mockery(SimpleRollOnSuccess::class);
        $simpleRollOnSuccess->shouldReceive('getDifficulty')
            ->andReturn($difficulty);
        $simpleRollOnSuccess->shouldReceive('isSuccessful')
            ->andReturn($isSuccessful);
        $simpleRollOnSuccess->shouldReceive('getResultCode')
            ->andReturn($resultCode);
        $simpleRollOnSuccess->shouldReceive('getRollOnQuality')
            ->andReturn($rollOnQuality);

        return $simpleRollOnSuccess;
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|RollOnQuality
     */
    private function createRollOnQuality($value)
    {
        $rollOnQuality = $this->mockery(RollOnQuality::class);
        $rollOnQuality->shouldReceive('getPreconditionsSum')
            ->andReturn('some preconditions sum');
        $rollOnQuality->shouldReceive('getValue')
            ->andReturn($value);
        $rollOnQuality->shouldReceive('getRoll')
            ->andReturn($roll = $this->mockery(Roll::class));
        $roll->shouldReceive('getValue')
            ->andReturn('some roll value');
        $roll->shouldReceive('getRolledNumbers')
            ->andReturn(['some', 'rolled', 'numbers']);

        return $rollOnQuality;
    }
}
