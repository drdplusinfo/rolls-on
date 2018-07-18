<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\QualityAndSuccess;

use DrdPlus\RollsOn\QualityAndSuccess\Requirements\AnimalDefiance;
use DrdPlus\RollsOn\QualityAndSuccess\Requirements\PreviousFailuresCount;
use DrdPlus\RollsOn\QualityAndSuccess\Requirements\Ride;
use DrdPlus\RollsOn\QualityAndSuccess\Requirements\RidingSkill;
use DrdPlus\RollsOn\Traps\RollOnAgility;

/**
 * @method RollOnAgility getRollOnQuality
 * @method string getResult
 */
class RollOnAnimalControl extends ExtendedRollOnSuccess
{
    const FATAL_FAILURE = 'fatal_failure';
    const MODERATE_FAILURE = 'moderate_failure';
    const SUCCESS = SimpleRollOnSuccess::DEFAULT_SUCCESS_RESULT_CODE;

    /**
     * @param RollOnAgility $rollOnAgility
     * @param AnimalDefiance $animalDefiance
     * @param Ride $ride
     * @param RidingSkill $ridingSkill
     * @param PreviousFailuresCount $previousFailuresCount
     */
    public function __construct(
        RollOnAgility $rollOnAgility,
        AnimalDefiance $animalDefiance,
        Ride $ride,
        RidingSkill $ridingSkill,
        PreviousFailuresCount $previousFailuresCount
    )
    {
        $toSuccessTrap = $animalDefiance->getValue() + $ride->getValue() - $ridingSkill->getValue() + $previousFailuresCount->getValue();
        $toModerateFailureTrap = $toSuccessTrap - 4;
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct(
            new SimpleRollOnSuccess($toModerateFailureTrap, $rollOnAgility, self::MODERATE_FAILURE, self::FATAL_FAILURE),
            new SimpleRollOnSuccess($toSuccessTrap, $rollOnAgility, self::SUCCESS, self::MODERATE_FAILURE)
        );
    }

    /**
     * @return RollOnAgility
     */
    public function getRollOnAgility(): RollOnAgility
    {
        return $this->getRollOnQuality();
    }

    /**
     * @return bool
     */
    public function isModerateFailure(): bool
    {
        return $this->getResult() === self::MODERATE_FAILURE;
    }

    /**
     * @return bool
     */
    public function isFatalFailure(): bool
    {
        return $this->getResult() === self::FATAL_FAILURE;
    }

    /**
     * @return bool
     */
    public function isFailure(): bool
    {
        // even moderate failure is failure on riding an animal
        return $this->isFatalFailure() || $this->isModerateFailure();
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return !$this->isFailure();
    }

}