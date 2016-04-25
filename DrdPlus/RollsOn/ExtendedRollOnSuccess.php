<?php
namespace DrdPlus\RollsOn;

use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class ExtendedRollOnSuccess extends StrictObject implements RollOnSuccess
{

    /**
     * @var RollOnQuality
     */
    private $rollOnQuality;
    /**
     * @var SimpleRollOnSuccess[]
     */
    private $rollsOnSuccess;

    public function __construct(
        SimpleRollOnSuccess $firstRollOnSuccess,
        SimpleRollOnSuccess $secondRollOnSuccess = null,
        SimpleRollOnSuccess $thirdRollOnSuccess = null
    )
    {
        $this->rollsOnSuccess = $this->grabOrderedRollsOnSuccess(func_get_args());
        $this->rollOnQuality = $this->grabRollOnQuality($this->rollsOnSuccess);
    }

    /**
     * @param array $constructorArguments
     * @return array|SimpleRollOnSuccess[]
     * @throws \DrdPlus\RollsOn\Exceptions\ExpectedRollsOnSuccessOnly
     * @throws \DrdPlus\RollsOn\Exceptions\EveryDifficultyShouldBeUnique
     * @throws \DrdPlus\RollsOn\Exceptions\EverySuccessCodeShouldBeUnique
     * @throws \DrdPlus\RollsOn\Exceptions\RollOnQualityHasToBeTheSame
     */
    private function grabOrderedRollsOnSuccess(array $constructorArguments)
    {
        $rollsOnSuccess = $this->removeNulls($constructorArguments);
        $this->guardRollsOnSuccessOnly($rollsOnSuccess);
        $this->guardUniqueDifficulties($rollsOnSuccess);
        $this->guardUniqueSuccessCodes($rollsOnSuccess);
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
            if (!$onFlyRollOnSuccess instanceof SimpleRollOnSuccess) {
                throw new Exceptions\ExpectedRollsOnSuccessOnly(
                    'Expected only ' . SimpleRollOnSuccess::class . ' (or null), got '
                    . ValueDescriber::describe($onFlyRollOnSuccess)
                );
            }
        }
    }

    /**
     * @param array|SimpleRollOnSuccess[] $rollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\EveryDifficultyShouldBeUnique
     */
    private function guardUniqueDifficulties(array $rollsOnSuccess)
    {
        $difficulties = [];
        /** @var SimpleRollOnSuccess $rollOnSuccess */
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
     * @param array|SimpleRollOnSuccess[] $rollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\EverySuccessCodeShouldBeUnique
     */
    private function guardUniqueSuccessCodes(array $rollsOnSuccess)
    {
        $successCodes = [];
        foreach ($rollsOnSuccess as $rollOnSuccess) {
            if ($rollOnSuccess->isSuccessful()) {
                $successCodes[] = $rollOnSuccess->getResultCode();
            }
        }
        if ($successCodes !== array_unique($successCodes)) {
            throw new Exceptions\EverySuccessCodeShouldBeUnique(
                'Expected only unique success codes, got ' . implode(',', $successCodes)
            );
        }
    }

    /**
     * @param array|SimpleRollOnSuccess[] $rollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\RollOnQualityHasToBeTheSame
     */
    private function guardSameRollOnQuality(array $rollsOnSuccess)
    {
        /** @var RollOnQuality $rollOnQuality */
        $rollOnQuality = null;
        foreach ($rollsOnSuccess as $rollOnSuccess) {
            if ($rollOnQuality === null) {
                $rollOnQuality = $rollOnSuccess->getRollOnQuality();
            } else {
                $secondRollOnQuality = $rollOnSuccess->getRollOnQuality();
                if ($rollOnQuality->getValue() !== $secondRollOnQuality->getValue()
                    || $rollOnQuality->getPreconditionsSum() !== $secondRollOnQuality->getPreconditionsSum()
                    || $rollOnQuality->getRoll()->getValue() !== $secondRollOnQuality->getRoll()->getValue()
                    || $rollOnQuality->getRoll()->getRolledNumbers() !== $secondRollOnQuality->getRoll()->getRolledNumbers()
                ) {
                    throw new Exceptions\RollOnQualityHasToBeTheSame(
                        'Expected same roll of quality for every roll on success, got one with '
                        . $this->describeRollOnQuality($rollOnQuality)
                        . ' and another with ' . $this->describeRollOnQuality($rollOnSuccess->getRollOnQuality())
                    );
                }
            }
        }
    }

    private function describeRollOnQuality(RollOnQuality $rollOnQuality)
    {
        return "sum of preconditions: {$rollOnQuality->getPreconditionsSum()}, value: {$rollOnQuality->getValue()}"
        . ", roll value {$rollOnQuality->getRoll()->getValue()}, rolled numbers "
        . implode(',', $rollOnQuality->getRoll()->getRolledNumbers());
    }

    /**
     * @param array|SimpleRollOnSuccess[] $rollsOnSuccess
     * @return array|SimpleRollOnSuccess[]
     */
    private function sortByDifficulty(array $rollsOnSuccess)
    {
        usort($rollsOnSuccess, function (SimpleRollOnSuccess $rollOnSuccess, SimpleRollOnSuccess $anotherRollOnSuccess) {
            if ($rollOnSuccess->getDifficulty() < $anotherRollOnSuccess->getDifficulty()) {
                return -1;
            } else { /** equation will never happen, @see \DrdPlus\RollsOn\ExtendedRollOnSuccess::guardUniqueDifficulties */
                return 1;
            }
        });

        return $rollsOnSuccess;
    }

    /**
     * @param array|SimpleRollOnSuccess[] $rollsOnSuccess
     * @return RollOnQuality
     */
    private function grabRollOnQuality(array $rollsOnSuccess)
    {
        /** @var SimpleRollOnSuccess $rollOnSuccess */
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
     * @return bool|SimpleRollOnSuccess
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

        return SimpleRollOnSuccess::FAIL_RESULT_CODE;
    }

    public function __toString()
    {
        return $this->getResultCode();
    }
}