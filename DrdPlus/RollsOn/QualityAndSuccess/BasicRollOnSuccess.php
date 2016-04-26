<?php
namespace DrdPlus\RollsOn\QualityAndSuccess;

use Granam\Boolean\BooleanInterface;

class BasicRollOnSuccess extends SimpleRollOnSuccess implements BooleanInterface
{

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->getResult();
    }

}