<?php
namespace DrdPlus\RollsOn;

interface RollOnSuccess
{

    /**
     * @return RollOnQuality
     */
    public function getRollOnQuality();

    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return string
     */
    public function getResultCode();

    /**
     * @return bool
     */
    public function isFailed();

    /**
     * @return string
     */
    public function __toString();

}