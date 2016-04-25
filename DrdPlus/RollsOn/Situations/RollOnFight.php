<?php
namespace DrdPlus\RollsOn\Situations;

use Drd\DiceRoll\Templates\Rollers\SpecificRolls\Roll2d6DrdPlus;
use Granam\Integer\Tools\ToInteger;

class RollOnFight extends RollOnSituation
{
    /**
     * @var int
     */
    private $fightNumber;

    /**
     * RollOnFight constructor.
     * @param int $fightNumber
     * @param Roll2d6DrdPlus $roll2d6Plus
     */
    public function __construct($fightNumber, Roll2d6DrdPlus $roll2d6Plus)
    {
        parent::__construct($roll2d6Plus);
        $this->fightNumber = ToInteger::toInteger($fightNumber);
    }

    /**
     * @return int
     */
    public function getFightNumber()
    {
        return $this->fightNumber;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->getFightNumber() + $this->getRoll2d6Plus()->getValue();
    }
}