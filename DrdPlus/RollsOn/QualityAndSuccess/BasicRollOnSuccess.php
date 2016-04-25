<?php
namespace DrdPlus\RollsOn\QualityAndSuccess;

use Granam\Boolean\BooleanInterface;

class BasicRollOnSuccess extends SimpleRollOnSuccess implements BooleanInterface
{
    const DEFAULT_SUCCESS_RESULT_CODE = 'success';

    /**
     * @param int $difficulty
     * @param RollOnQuality $rollOnQuality
     * @param string $successCode = 'success'
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function __construct(
        $difficulty,
        RollOnQuality $rollOnQuality,
        $successCode = self::DEFAULT_SUCCESS_RESULT_CODE
    )
    {
        parent::__construct($difficulty, $rollOnQuality, $successCode);
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->getResult();
    }

}