<?php
namespace DrdPlus\RollsOn\QualityAndSuccess;

interface RollOnSuccess
{

    /**
     * @return RollOnQuality
     */
    public function getRollOnQuality();

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @return string|int|float|bool
     */
    public function getResult();

    /**
     * @return bool
     */
    public function isFailure(): bool;

    /**
     * @return string
     */
    public function __toString();

}