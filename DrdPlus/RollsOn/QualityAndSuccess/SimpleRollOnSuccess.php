<?php
namespace DrdPlus\RollsOn\QualityAndSuccess;

use Drd\DiceRoll\Roll;
use Granam\Integer\Tools\ToInteger;
use Granam\Scalar\Tools\ToString;
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
    private $successCode;
    /**
     * @var string
     */
    private $failureCode;

    const DEFAULT_SUCCESS_RESULT_CODE = 'success';
    const DEFAULT_FAILURE_RESULT_CODE = 'failure';

    /**
     * @param int $difficulty
     * @param RollOnQuality $rollOnQuality
     * @param string $successCode
     * @param string $failureCode
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function __construct(
        $difficulty,
        RollOnQuality $rollOnQuality,
        $successCode = self::DEFAULT_SUCCESS_RESULT_CODE,
        $failureCode = self::DEFAULT_FAILURE_RESULT_CODE
    )
    {
        $this->difficulty = ToInteger::toInteger($difficulty);
        $this->rollOnQuality = $rollOnQuality;
        $this->successCode = ToString::toString($successCode);
        $this->failureCode = ToString::toString($failureCode);
    }

    /**
     * @return int
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @return RollOnQuality
     */
    public function getRollOnQuality()
    {
        return $this->rollOnQuality;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getResult();
    }

    /**
     * @return bool
     */
    protected function getResult()
    {
        return $this->getDifficulty() <= $this->getRollOnQuality()->getValue();
    }

    /**
     * @return string
     */
    public function getResultCode()
    {
        return $this->isSuccessful()
            ? $this->successCode
            : $this->failureCode;
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return !$this->getResult();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getResultCode();
    }

}