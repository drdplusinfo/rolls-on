<?php
namespace DrdPlus\RollsOn;

use Drd\DiceRoll\Roller;
use Drd\DiceRoll\Templates\Rollers\Roller2d6DrdPlus;
use Granam\Strict\Object\StrictObject;

class RollsOn extends StrictObject
{
    /**
     * @var Roller2d6DrdPlus
     */
    private $roller2d6DrdPlus;

    public function __construct(Roller2d6DrdPlus $roller2d6DrdPlus)
    {
        $this->roller2d6DrdPlus = $roller2d6DrdPlus;
    }

    /**
     * See DrD+ PPH page 101 left column
     * @param int $fightNumber
     * @return RollOnFight
     */
    public function makeRollOnFight($fightNumber)
    {
        return new RollOnFight($fightNumber, $this->roller2d6DrdPlus->roll());
    }

    /**
     * @param int $preconditionsSum
     * @param Roller $roller
     * @return RollOnQuality
     */
    public function makeRollOnQuality($preconditionsSum, Roller $roller)
    {
        return new RollOnQuality($preconditionsSum, $roller->roll());
    }

    /**
     * @param int $difficulty
     * @param int $preconditionsSum
     * @param Roller $roller
     * @return BaseRollOnSuccess
     */
    public function makeBaseRollOnSuccess($difficulty, $preconditionsSum, Roller $roller)
    {
        return new BaseRollOnSuccess($difficulty, $this->makeRollOnQuality($preconditionsSum, $roller));
    }

}