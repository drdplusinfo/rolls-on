<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll1d6;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Properties\Property;

class ShortRollOnWill extends ShortRollOnProperty
{
    /**
     * @param Will $will
     * @param Roll1d6 $roll1d6
     */
    public function __construct(Will $will, Roll1d6 $roll1d6)
    {
        parent::__construct($will, $roll1d6);
    }

    /**
     * @return Will|Property
     */
    public function getWill()
    {
        return $this->getProperty();
    }
}