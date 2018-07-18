<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll1d6;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Property;

class ShortRollOnStrength extends ShortRollOnProperty
{

    /**
     * @param Strength $strength
     * @param Roll1d6 $roll1d6
     */
    public function __construct(Strength $strength, Roll1d6 $roll1d6)
    {
        parent::__construct($strength, $roll1d6);
    }

    /**
     * @return Strength|Property
     */
    public function getStrength()
    {
        return $this->getProperty();
    }
}