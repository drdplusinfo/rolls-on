<?php
namespace DrdPlus\Tests\RollsOn\QualityAndSuccess;

use Drd\DiceRoll\Roll;
use DrdPlus\RollsOn\QualityAndSuccess\Requirements\AnimalDefiance;
use DrdPlus\RollsOn\QualityAndSuccess\Requirements\Ride;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnAnimalControl;
use DrdPlus\RollsOn\Traps\RollOnAgility;
use Granam\Tests\Tools\TestWithMockery;

class RollOnAnimalControlTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $rollOnAnimalControl = new RollOnAnimalControl(
            $rollOnAgility = $this->createRollOnAgility(321),
            $animalDefiance = $this->createAnimalDefiance(123),
            $ride = $this->createRide(456)
        );

        self::assertSame($rollOnAgility, $rollOnAnimalControl->getRollOnAgility());
        self::assertSame($rollOnAgility, $rollOnAnimalControl->getRollOnQuality());
    }

    /**
     * @param $rollValue
     * @return \Mockery\MockInterface|RollOnAgility
     */
    private function createRollOnAgility($rollValue)
    {
        $rollOnAgility = $this->mockery(RollOnAgility::class);
        $rollOnAgility->shouldReceive('getValue')
            ->andReturn($rollValue);
        $rollOnAgility->shouldReceive('getPreconditionsSum')
            ->andReturn(67890 /* whatever */);
        $rollOnAgility->shouldReceive('getRoll')
            ->andReturn($roll = $this->mockery(Roll::class));
        $roll->shouldReceive('getValue')
            ->andReturn(12345 /* whatever */);
        $roll->shouldReceive('getRolledNumbers')
            ->andReturn([1, 3, 4]);

        return $rollOnAgility;
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|AnimalDefiance
     */
    private function createAnimalDefiance($value)
    {
        $animalDefiance = $this->mockery(AnimalDefiance::class);
        $animalDefiance->shouldReceive('getValue')
            ->andReturn($value);

        return $animalDefiance;
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|Ride
     */
    private function createRide($value)
    {
        $ride = $this->mockery(Ride::class);
        $ride->shouldReceive('getValue')
            ->andReturn($value);

        return $ride;
    }

    /**
     * @test
     * @dataProvider provideValuesToModerateFail
     * @param $rollValue
     * @param $defianceValue
     * @param $rideValue
     * @param $isModerateFailure
     * @param $isSuccess
     */
    public function I_can_find_out_if_failed_just_moderately($rollValue, $defianceValue, $rideValue, $isModerateFailure, $isSuccess)
    {
        $rollOnAnimalControl = new RollOnAnimalControl(
            $this->createRollOnAgility($rollValue),
            $this->createAnimalDefiance($defianceValue),
            $this->createRide($rideValue)
        );

        self::assertSame($isModerateFailure, $rollOnAnimalControl->isModerateFailure());
        self::assertSame($isSuccess, $rollOnAnimalControl->isSuccess());
        self::assertSame(!$isSuccess, $rollOnAnimalControl->isFailure());
    }

    public function provideValuesToModerateFail()
    {
        return [
            [10, 5, 5, false, true],
            [10, 5, 6, true, false], // in case of riding on animal even partial failure is failure
            [3, 3, 5, false, false],
        ];
    }

    /**
     * @test
     * @dataProvider provideValuesToFatalFail
     * @param $rollValue
     * @param $defianceValue
     * @param $rideValue
     * @param $isFatalFailure
     */
    public function I_can_find_out_if_failed_fataly($rollValue, $defianceValue, $rideValue, $isFatalFailure)
    {
        $rollOnAnimalControl = new RollOnAnimalControl(
            $this->createRollOnAgility($rollValue),
            $this->createAnimalDefiance($defianceValue),
            $this->createRide($rideValue)
        );

        self::assertSame($isFatalFailure, $rollOnAnimalControl->isModerateFailure());
    }

    public function provideValuesToFatalFail()
    {
        return [
            [10, 5, 5, false],
            [10, 5, 6, true],
        ];
    }
}
