<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

class RollOnStrength extends RollOnQuality
{
    /**
     * @var Strength
     */
    private $strength;

    /**
     * @param Strength $strength
     * @param Roll2d6DrdPlus $roll2d6DrdPlus
     */
    public function __construct(Strength $strength, Roll2d6DrdPlus $roll2d6DrdPlus)
    {
        $this->strength = $strength;
        parent::__construct($strength->getValue(), $roll2d6DrdPlus);
    }

    /**
     * @return Strength
     */
    public function getStrength(): Strength
    {
        return $this->strength;
    }
}