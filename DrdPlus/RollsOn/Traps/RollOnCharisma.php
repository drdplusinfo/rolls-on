<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

class RollOnCharisma extends RollOnQuality
{
    /**
     * @var Charisma
     */
    private $charisma;

    /**
     * @param Charisma $charisma
     * @param Roll2d6DrdPlus $roll2d6DrdPlus
     */
    public function __construct(Charisma $charisma, Roll2d6DrdPlus $roll2d6DrdPlus)
    {
        $this->charisma = $charisma;
        parent::__construct($charisma->getValue(), $roll2d6DrdPlus);
    }

    /**
     * @return Charisma
     */
    public function getCharisma(): Charisma
    {
        return $this->charisma;
    }
}