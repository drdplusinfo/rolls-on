<?php
namespace DrdPlus\RollsOn;

use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class ExtendedRollOnSuccess extends StrictObject
{

    /**
     * @var RollOnQuality
     */
    private $rollOnQuality;
    /**
     * @var RollOnSuccess[]
     */
    private $rollsOnSuccess;

    public function __construct(
        RollOnSuccess $firstRollOnSuccess,
        RollOnSuccess $secondRollOnSuccess = null,
        RollOnSuccess $thirdRollOnSuccess = null
    )
    {
        $this->rollsOnSuccess = $this->grabOrderedRollsOnSuccess(func_get_args());
        $this->rollOnQuality = $this->grabRollOnQuality($this->rollsOnSuccess);
    }

    /**
     * @param array $constructorArguments
     * @return array|RollOnSuccess[]
     * @throws \DrdPlus\RollsOn\Exceptions\ExpectedRollsOnSuccessOnly
     * @throws \DrdPlus\RollsOn\Exceptions\EveryDifficultyShouldBeUnique
     * @throws \DrdPlus\RollsOn\Exceptions\EverySuccessCodeShouldBeUnique
     * @throws \DrdPlus\RollsOn\Exceptions\RollOnQualityHasToBeTheSame
     */
    private function grabOrderedRollsOnSuccess(array $constructorArguments)
    {
        $rollsOnSuccess = $this->removeNulls($constructorArguments);
        $this->guardRollsOnSuccessOnly($rollsOnSuccess);
        $this->guardDifficultiesUnique($rollsOnSuccess);
        $this->guardSuccessCodesUnique($rollsOnSuccess);
        $this->guardSameRollOnQuality($rollsOnSuccess);

        return $this->sortByDifficulty($rollsOnSuccess);
    }

    private function removeNulls(array $values)
    {
        return array_filter(
            $values,
            function ($value) {
                return $value !== null;
            }
        );
    }

    /**
     * @param array $onFlyRollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\ExpectedRollsOnSuccessOnly
     */
    private function guardRollsOnSuccessOnly(array $onFlyRollsOnSuccess)
    {
        foreach ($onFlyRollsOnSuccess as $onFlyRollOnSuccess) {
            if (!$onFlyRollOnSuccess instanceof RollOnSuccess) {
                throw new Exceptions\ExpectedRollsOnSuccessOnly(
                    'Expected only ' . RollOnSuccess::class . ' (or null), got '
                    . ValueDescriber::describe($onFlyRollOnSuccess)
                );
            }
        }
    }

    /**
     * @param array|RollOnSuccess[] $rollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\EveryDifficultyShouldBeUnique
     */
    private function guardDifficultiesUnique(array $rollsOnSuccess)
    {
        $difficulties = [];
        /** @var RollOnSuccess $rollOnSuccess */
        foreach ($rollsOnSuccess as $rollOnSuccess) {
            $difficulties[] = $rollOnSuccess->getDifficulty();
        }
        if ($difficulties !== array_unique($difficulties)) {
            throw new Exceptions\EveryDifficultyShouldBeUnique(
                'Expected only unique difficulties, got ' . implode(',', $difficulties)
            );
        }
    }

    /**
     * @param array|RollOnSuccess[] $rollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\EverySuccessCodeShouldBeUnique
     */
    private function guardSuccessCodesUnique(array $rollsOnSuccess)
    {
        $successCodes = [];
        foreach ($rollsOnSuccess as $rollOnSuccess) {
            $successCodes[] = $rollOnSuccess->getResultCode();
        }
        if ($successCodes !== array_unique($successCodes)) {
            throw new Exceptions\EverySuccessCodeShouldBeUnique(
                'Expected only unique difficulties, got ' . implode(',', $successCodes)
            );
        }
    }

    /**
     * @param array|RollOnSuccess[] $rollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\RollOnQualityHasToBeTheSame
     */
    private function guardSameRollOnQuality(array $rollsOnSuccess)
    {
        $rollOnQuality = null;
        foreach ($rollsOnSuccess as $rollOnSuccess) {
            if ($rollOnQuality === null) {
                $rollOnQuality = $rollOnSuccess->getRollOnQuality();
            } else if ($rollOnQuality !== $rollOnSuccess->getRollOnQuality()) {
                throw new Exceptions\RollOnQualityHasToBeTheSame(
                    'Expected same roll of quality for every roll on success, got '
                    . var_export($rollOnQuality, true)
                    . ' and ' . var_export($rollOnSuccess->getRollOnQuality(), true)
                );
            }
        }
    }

    /**
     * @param array|RollOnSuccess[] $rollsOnSuccess
     * @return array|RollOnSuccess[]
     */
    private function sortByDifficulty(array $rollsOnSuccess)
    {
        usort($rollsOnSuccess, function (RollOnSuccess $rollOnSuccess, RollOnSuccess $anotherRollOnSuccess) {
            if ($rollOnSuccess->getDifficulty() < $anotherRollOnSuccess->getDifficulty()) {
                return -1;
            }
            if ($rollOnSuccess->getDifficulty() > $anotherRollOnSuccess->getDifficulty()) {
                return 1;
            }

            return 0;
        });

        return $rollsOnSuccess;
    }

    /**
     * @param array|RollOnSuccess[] $rollsOnSuccess
     * @return RollOnQuality
     */
    private function grabRollOnQuality(array $rollsOnSuccess)
    {
        /** @var RollOnSuccess $rollOnSuccess */
        $rollOnSuccess = current($rollsOnSuccess);

        return $rollOnSuccess->getRollOnQuality();
    }

    /**
     * @return RollOnQuality
     */
    public function getRollOnQuality()
    {
        return $this->rollOnQuality;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        $resultRollOnSuccess = $this->getResultRollOnSuccess();
        if ($resultRollOnSuccess) {
            return true;
        }

        return false;
    }

    /**
     * @return bool|RollOnSuccess
     */
    protected function getResultRollOnSuccess()
    {
        foreach ($this->rollsOnSuccess as $rollOnSuccess) {
            if ($rollOnSuccess->isSuccessful()) {
                return $rollOnSuccess;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return !$this->isSuccessful();
    }

    /**
     * @return string
     */
    public function getResultCode()
    {
        $resultRollOnSuccess = $this->getResultRollOnSuccess();
        if ($resultRollOnSuccess) {
            return $resultRollOnSuccess->getResultCode();
        }

        return RollOnSuccess::FAIL_RESULT_CODE;
    }

    public function __toString()
    {
        return $this->getResultCode();
    }
}