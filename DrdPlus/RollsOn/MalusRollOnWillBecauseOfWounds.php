<?php
namespace DrdPlus\RollsOn;

use DrdPlus\RollsOn\QualityAndSuccess\ExtendedRollOnSuccess;
use DrdPlus\RollsOn\QualityAndSuccess\SimpleRollOnSuccess;
use Granam\Integer\IntegerInterface;
use Granam\Tools\ValueDescriber;

/**
 * @method RollOnWill getRollOnQuality
 */
class MalusRollOnWillBecauseOfWounds extends ExtendedRollOnSuccess implements IntegerInterface
{

    /**
     * @var RollOnWill
     */
    private $rollOnWill;

    const HIGHEST_MALUS = 'highest_malus';
    const MEDIUM_MALUS = 'medium_malus';
    const LOWEST_MALUS = 'lowest_malus';
    const WITHOUT_MALUS = 'without_malus';

    public function __construct(RollOnWill $rollOnWill)
    {
        $this->rollOnWill = $rollOnWill;
        parent::__construct(
            new SimpleRollOnSuccess(5, $rollOnWill, self::MEDIUM_MALUS, self::HIGHEST_MALUS),
            new SimpleRollOnSuccess(10, $rollOnWill, self::LOWEST_MALUS),
            new SimpleRollOnSuccess(15, $rollOnWill, self::WITHOUT_MALUS)
        );
    }

    /**
     * @return RollOnWill
     */
    public function getRollOnWill()
    {
        return $this->rollOnWill;
    }

    public function getValue()
    {
        switch ($this->getResultCode()) {
            case self::HIGHEST_MALUS :
                return -3;
            case self::MEDIUM_MALUS :
                return -2;
            case self::LOWEST_MALUS :
                return -1;
            case self::WITHOUT_MALUS :
                return 0;
            default :
                throw new Exceptions\UnknownRollOnWillResultCode(
                    'Unknown result code of roll on will: ' . ValueDescriber::describe($this->getResultCode())
                );
        }
    }
}