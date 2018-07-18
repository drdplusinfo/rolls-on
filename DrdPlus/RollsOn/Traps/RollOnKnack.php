<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

class RollOnKnack extends RollOnQuality
{
    /**
     * @var Knack
     */
    private $knack;

    /**
     * @param Knack $knack
     * @param Roll2d6DrdPlus $roll2d6DrdPlus
     */
    public function __construct(Knack $knack, Roll2d6DrdPlus $roll2d6DrdPlus)
    {
        $this->knack = $knack;
        parent::__construct($knack->getValue(), $roll2d6DrdPlus);
    }

    /**
     * @return Knack
     */
    public function getKnack(): Knack
    {
        return $this->knack;
    }
}