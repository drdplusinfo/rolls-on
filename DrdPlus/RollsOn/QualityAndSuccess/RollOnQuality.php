<?php
namespace DrdPlus\RollsOn\QualityAndSuccess;

use Drd\DiceRoll\Roll;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

class RollOnQuality extends StrictObject implements IntegerInterface
{
    /**
     * @var int
     */
    private $preconditionsSum;
    /**
     * @var Roll
     */
    private $roll;

    public function __construct($preconditionsSum, Roll $roll)
    {
        $this->preconditionsSum = ToInteger::toInteger($preconditionsSum);
        $this->roll = $roll;
    }

    /**
     * @return int
     */
    public function getPreconditionsSum()
    {
        return $this->preconditionsSum;
    }

    /**
     * @return Roll
     */
    public function getRoll()
    {
        return $this->roll;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->getPreconditionsSum() + $this->getRoll()->getValue();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue();
    }

}