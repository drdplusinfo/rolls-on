<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll1d6;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Property;

class ShortRollOnCharisma extends ShortRollOnProperty
{
    /**
     * @param Charisma $charisma
     * @param Roll1d6 $roll1d6
     */
    public function __construct(Charisma $charisma, Roll1d6 $roll1d6)
    {
        parent::__construct($charisma, $roll1d6);
    }

    /**
     * @return Charisma|Property
     */
    public function getCharisma()
    {
        return $this->getProperty();
    }
}