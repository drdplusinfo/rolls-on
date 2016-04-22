<?php
namespace DrdPlus\RollsOn;

use Drd\DiceRoll\Roll;
use Granam\Boolean\BooleanInterface;
use Granam\Strict\Object\StrictObject;

class BaseRollOnSuccess extends StrictObject implements BooleanInterface
{
    /**
     * @var int
     */
    private $difficulty;
    /**
     * @var int
     */
    private $propertyValue;
    /**
     * @var Roll
     */
    private $roll;

    /**
     * @param int $difficulty
     * @param int $propertyValue
     * @param Roll $roll
     */
    public function __construct($difficulty, $propertyValue, Roll $roll)
    {
        $this->difficulty = $difficulty;
        $this->propertyValue = $propertyValue;
        $this->roll = $roll;
    }

    /**
     * @return int
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @return int
     */
    public function getPropertyValue()
    {
        return $this->propertyValue;
    }

    /**
     * @return Roll
     */
    public function getRoll()
    {
        return $this->roll;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getValue();
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return !$this->getValue();
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->getDifficulty() <= $this->getPropertyValue() + $this->getRoll()->getValue();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue()
            ? 'success'
            : 'fail';
    }

}