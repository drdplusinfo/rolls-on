<?php
namespace DrdPlus\RollsOn\Traps;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Derived\Senses;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

class RollOnSenses extends RollOnQuality
{
    /**
     * @var Senses
     */
    private $senses;

    /**
     * @param Senses $senses
     * @param Roll2d6DrdPlus $roll2d6DrdPlus
     */
    public function __construct(Senses $senses, Roll2d6DrdPlus $roll2d6DrdPlus)
    {
        $this->senses = $senses;
        parent::__construct($senses->getValue(), $roll2d6DrdPlus);
    }

    /**
     * @return Senses
     */
    public function getSenses()
    {
        return $this->senses;
    }
}