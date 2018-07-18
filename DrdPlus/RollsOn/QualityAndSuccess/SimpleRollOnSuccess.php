<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\QualityAndSuccess;

use DrdPlus\DiceRolls\Roll;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Scalar\ScalarInterface;
use Granam\Scalar\Tools\ToScalar;
use Granam\Strict\Object\StrictObject;

class SimpleRollOnSuccess extends StrictObject implements RollOnSuccess
{
    /**
     * @var int
     */
    private $difficulty;
    /**
     * @var Roll
     */
    private $rollOnQuality;
    /**
     * @var string
     */
    private $successValue;
    /**
     * @var string
     */
    private $failureValue;

    const DEFAULT_SUCCESS_RESULT_CODE = 'success';
    const DEFAULT_FAILURE_RESULT_CODE = 'failure';

    /**
     * @param int|IntegerInterface $difficulty
     * @param RollOnQuality $rollOnQuality
     * @param string|int|float|bool|ScalarInterface $successValue
     * @param string|int|float|bool|ScalarInterface $failureValue
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function __construct(
        $difficulty,
        RollOnQuality $rollOnQuality,
        $successValue = self::DEFAULT_SUCCESS_RESULT_CODE,
        $failureValue = self::DEFAULT_FAILURE_RESULT_CODE
    )
    {
        $this->difficulty = ToInteger::toInteger($difficulty);
        $this->rollOnQuality = $rollOnQuality;
        $this->successValue = ToScalar::toScalar($successValue);
        $this->failureValue = ToScalar::toScalar($failureValue);
    }

    /**
     * @return int
     */
    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    /**
     * @return RollOnQuality
     */
    public function getRollOnQuality(): RollOnQuality
    {
        return $this->rollOnQuality;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getDifficulty() <= $this->getRollOnQuality()->getValue();
    }

    /**
     * @return string|int|float|bool
     */
    public function getResult()
    {
        return $this->isSuccess()
            ? $this->successValue
            : $this->failureValue;
    }

    /**
     * @return bool
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getResult();
    }

}