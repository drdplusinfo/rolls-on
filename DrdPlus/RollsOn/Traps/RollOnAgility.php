<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

class RollOnAgility extends RollOnQuality
{
    /**
     * @var Agility
     */
    private $agility;

    /**
     * @param Agility $agility
     * @param Roll2d6DrdPlus $roll2d6DrdPlus
     */
    public function __construct(Agility $agility, Roll2d6DrdPlus $roll2d6DrdPlus)
    {
        $this->agility = $agility;
        parent::__construct($agility->getValue(), $roll2d6DrdPlus);
    }

    /**
     * @return Agility
     */
    public function getAgility(): Agility
    {
        return $this->agility;
    }
}