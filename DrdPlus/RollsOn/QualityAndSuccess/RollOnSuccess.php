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
    public function isSuccess();

    /**
     * @return string
     */
    public function getResult();

    /**
     * @return bool
     */
    public function isFailure();

    /**
     * @return string
     */
    public function __toString();

}