<?php
declare(strict_types=1);

namespace DrdPlus\RollsOn;

use DrdPlus\DiceRolls\Roller;
use DrdPlus\DiceRolls\Templates\Rollers\Roller2d6DrdPlus;
use DrdPlus\Properties\Base\Will;
use DrdPlus\RollsOn\QualityAndSuccess\BasicRollOnSuccess;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\RollsOn\Situations\RollOnFight;
use DrdPlus\RollsOn\Traps\RollOnWillAgainstMalus;
use DrdPlus\RollsOn\Traps\RollOnWill;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

class RollsOnFactory extends StrictObject
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
     * @param int|IntegerInterface $fightNumber
     * @return RollOnFight
     */
    public function makeRollOnFight($fightNumber): RollOnFight
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new RollOnFight($fightNumber, $this->roller2d6DrdPlus->roll());
    }

    /**
     * @param int|IntegerInterface $preconditionsSum
     * @param Roller $roller
     * @return RollOnQuality
     */
    public function makeRollOnQuality($preconditionsSum, Roller $roller): RollOnQuality
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new RollOnQuality($preconditionsSum, $roller->roll());
    }

    /**
     * @param Will $will
     * @return RollOnWillAgainstMalus
     */
    public function makeMalusRollOnWill(Will $will): RollOnWillAgainstMalus
    {
        return new RollOnWillAgainstMalus(new RollOnWill($will, $this->roller2d6DrdPlus->roll()));
    }

    /**
     * @param int|IntegerInterface $difficulty
     * @param int|IntegerInterface $preconditionsSum
     * @param Roller $roller
     * @return BasicRollOnSuccess
     */
    public function makeBasicRollOnSuccess($difficulty, $preconditionsSum, Roller $roller): BasicRollOnSuccess
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new BasicRollOnSuccess($difficulty, $this->makeRollOnQuality($preconditionsSum, $roller));
    }

}