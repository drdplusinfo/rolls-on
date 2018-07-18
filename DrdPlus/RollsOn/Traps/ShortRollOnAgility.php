<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll1d6;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Property;

class ShortRollOnAgility extends ShortRollOnProperty
{
    /**
     * @param Agility $agility
     * @param Roll1d6 $roll1d6
     */
    public function __construct(Agility $agility, Roll1d6 $roll1d6)
    {
        parent::__construct($agility, $roll1d6);
    }

    /**
     * @return Agility|Property
     */
    public function getAgility()
    {
        return $this->getProperty();
    }
}