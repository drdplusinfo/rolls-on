<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Will;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

class RollOnWill extends RollOnQuality
{
    /**
     * @var Will
     */
    private $will;

    /**
     * @param Will $will
     * @param Roll2d6DrdPlus $roll2d6DrdPlus
     */
    public function __construct(Will $will, Roll2d6DrdPlus $roll2d6DrdPlus)
    {
        $this->will = $will;
        parent::__construct($will->getValue(), $roll2d6DrdPlus);
    }

    /**
     * @return Will
     */
    public function getWill(): Will
    {
        return $this->will;
    }
}