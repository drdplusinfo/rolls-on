<?php
namespace DrdPlus\RollsOn;

use Drd\DiceRoll\Roll;
use Granam\Integer\Tools\ToInteger;
use Granam\Scalar\Tools\ToString;
use Granam\Strict\Object\StrictObject;

class RollOnSuccess extends StrictObject
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
     * @param int $difficulty
     * @param RollOnQuality $rollOnQuality
     * @param string $successCode
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function __construct($difficulty, RollOnQuality $rollOnQuality, $successCode)
    {
        $this->difficulty = ToInteger::toInteger($difficulty);
        $this->rollOnQuality = $rollOnQuality;
        $this->successCode = ToString::toString($successCode);
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

    const FAIL_RESULT_CODE = 'fail';

    /**
     * @return string
     */
    public function getResultCode()
    {
        return $this->isSuccessful()
            ? $this->successCode
            : self::FAIL_RESULT_CODE;
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