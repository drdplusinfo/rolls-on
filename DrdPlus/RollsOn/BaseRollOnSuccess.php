<?php
namespace DrdPlus\RollsOn;

use Drd\DiceRoll\Roll;
use Granam\Boolean\BooleanInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

class BaseRollOnSuccess extends StrictObject implements BooleanInterface
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
     * @param int $difficulty
     * @param RollOnQuality $rollOnQuality
     */
    public function __construct($difficulty, RollOnQuality $rollOnQuality)
    {
        $this->difficulty = ToInteger::toInteger($difficulty);
        $this->rollOnQuality = $rollOnQuality;
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
        return $this->getValue();
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->getDifficulty() <= $this->getRollOnQuality()->getValue();
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return !$this->getValue();
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