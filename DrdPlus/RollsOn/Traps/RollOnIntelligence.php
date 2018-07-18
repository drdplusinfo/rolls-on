<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn\Traps;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

class RollOnIntelligence extends RollOnQuality
{
    /**
     * @var Intelligence
     */
    private $intelligence;

    /**
     * @param Intelligence $intelligence
     * @param Roll2d6DrdPlus $roll2d6DrdPlus
     */
    public function __construct(Intelligence $intelligence, Roll2d6DrdPlus $roll2d6DrdPlus)
    {
        $this->intelligence = $intelligence;
        parent::__construct($intelligence->getValue(), $roll2d6DrdPlus);
    }

    /**
     * @return Intelligence
     */
    public function getIntelligence(): Intelligence
    {
        return $this->intelligence;
    }
}