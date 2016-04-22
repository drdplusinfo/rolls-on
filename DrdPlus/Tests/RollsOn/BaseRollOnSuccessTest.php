<?php
namespace DrdPlus\Tests\RollsOn;

use Drd\DiceRoll\Roll;
use DrdPlus\RollsOn\BaseRollOnSuccess;
use Granam\Boolean\BooleanInterface;
use Granam\Tests\Tools\TestWithMockery;

class BaseRollOnSuccessTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideDifficultyAndPropertyWithRoll
     * @param $difficulty
     * @param $propertyValue
     * @param Roll $roll
     * @param $shouldSuccess
     */
    public function I_can_use_it($difficulty, $propertyValue, Roll $roll, $shouldSuccess)
    {
        $baseRollOnSuccess = new BaseRollOnSuccess($difficulty, $propertyValue, $roll);

        self::assertInstanceOf(BooleanInterface::class, $baseRollOnSuccess);
        self::assertSame($difficulty, $baseRollOnSuccess->getDifficulty());
        self::assertSame($propertyValue, $baseRollOnSuccess->getPropertyValue());
        self::assertSame($roll, $baseRollOnSuccess->getRoll());

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
            [123, 456, $this->createRoll(789), true],
            [999, 23, $this->createRoll(44), false],
            [0, 0, $this->createRoll(0), true],
            [1, 0, $this->createRoll(0), false],
            [1, 1, $this->createRoll(0), true],
            [1, 1, $this->createRoll(0), true],
            [1, 0, $this->createRoll(1), true],
        ];
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|Roll
     */
    private function createRoll($value)
    {
        $roll = $this->mockery(Roll::class);
        $roll->shouldReceive('getValue')
            ->andReturn($value);

        return $roll;
    }
}
