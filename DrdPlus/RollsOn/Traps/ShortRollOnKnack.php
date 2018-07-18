<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll1d6;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Property;

class ShortRollOnKnack extends ShortRollOnProperty
{
    /**
     * @param Knack $knack
     * @param Roll1d6 $roll1d6
     */
    public function __construct(Knack $knack, Roll1d6 $roll1d6)
    {
        parent::__construct($knack, $roll1d6);
    }

    /**
     * @return Knack|Property
     */
    public function getKnack()
    {
        return $this->getProperty();
    }
}