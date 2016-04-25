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
        SimpleRollOnSuccess $firstSimpleRollOnSuccess,
        SimpleRollOnSuccess $secondSimpleRollOnSuccess = null,
        SimpleRollOnSuccess $thirdSimpleRollOnSuccess = null
    )
    {
        $this->rollsOnSuccess = $this->grabOrderedRollsOnSuccess(func_get_args());
        $this->rollOnQuality = $this->grabRollOnQuality($this->rollsOnSuccess);
    }

    /**
     * @param array $constructorArguments
     * @return array|SimpleRollOnSuccess[]
     * @throws \DrdPlus\RollsOn\Exceptions\ExpectedSimpleRollsOnSuccessOnly
     * @throws \DrdPlus\RollsOn\Exceptions\EveryDifficultyShouldBeUnique
     * @throws \DrdPlus\RollsOn\Exceptions\EverySuccessCodeShouldBeUnique
     * @throws \DrdPlus\RollsOn\Exceptions\RollOnQualityHasToBeTheSame
     */
    private function grabOrderedRollsOnSuccess(array $constructorArguments)
    {
        $simpleRollsOnSuccess = $this->removeNulls($constructorArguments);
        $this->guardSimpleRollsOnSuccessOnly($simpleRollsOnSuccess);
        $this->guardUniqueDifficulties($simpleRollsOnSuccess);
        $this->guardUniqueSuccessCodes($simpleRollsOnSuccess);
        $this->guardSameRollOnQuality($simpleRollsOnSuccess);

        return $this->sortByDifficultyDescending($simpleRollsOnSuccess);
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
     * @param array $simpleRollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\ExpectedSimpleRollsOnSuccessOnly
     */
    private function guardSimpleRollsOnSuccessOnly(array $simpleRollsOnSuccess)
    {
        foreach ($simpleRollsOnSuccess as $simpleRollOnSuccess) {
            if (!$simpleRollOnSuccess instanceof SimpleRollOnSuccess) {
                throw new Exceptions\ExpectedSimpleRollsOnSuccessOnly(
                    'Expected only ' . SimpleRollOnSuccess::class . ' (or null), got '
                    . ValueDescriber::describe($simpleRollOnSuccess)
                );
            }
        }
    }

    /**
     * @param array|SimpleRollOnSuccess[] $simpleRollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\EveryDifficultyShouldBeUnique
     */
    private function guardUniqueDifficulties(array $simpleRollsOnSuccess)
    {
        $difficulties = [];
        /** @var SimpleRollOnSuccess $simpleRollOnSuccess */
        foreach ($simpleRollsOnSuccess as $simpleRollOnSuccess) {
            $difficulties[] = $simpleRollOnSuccess->getDifficulty();
        }
        if ($difficulties !== array_unique($difficulties)) {
            throw new Exceptions\EveryDifficultyShouldBeUnique(
                'Expected only unique difficulties, got ' . implode(',', $difficulties)
            );
        }
    }

    /**
     * @param array|SimpleRollOnSuccess[] $simpleRollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\EverySuccessCodeShouldBeUnique
     */
    private function guardUniqueSuccessCodes(array $simpleRollsOnSuccess)
    {
        $successCodes = [];
        foreach ($simpleRollsOnSuccess as $simpleRollOnSuccess) {
            if ($simpleRollOnSuccess->isSuccessful()) {
                $successCodes[] = $simpleRollOnSuccess->getResultCode();
            }
        }
        if ($successCodes !== array_unique($successCodes)) {
            throw new Exceptions\EverySuccessCodeShouldBeUnique(
                'Expected only unique success codes, got ' . implode(',', $successCodes)
            );
        }
    }

    /**
     * @param array|SimpleRollOnSuccess[] $simpleRollsOnSuccess
     * @throws \DrdPlus\RollsOn\Exceptions\RollOnQualityHasToBeTheSame
     */
    private function guardSameRollOnQuality(array $simpleRollsOnSuccess)
    {
        /** @var RollOnQuality $rollOnQuality */
        $rollOnQuality = null;
        foreach ($simpleRollsOnSuccess as $simpleRollOnSuccess) {
            if ($rollOnQuality === null) {
                $rollOnQuality = $simpleRollOnSuccess->getRollOnQuality();
            } else {
                $secondRollOnQuality = $simpleRollOnSuccess->getRollOnQuality();
                if ($rollOnQuality->getValue() !== $secondRollOnQuality->getValue()
                    || $rollOnQuality->getPreconditionsSum() !== $secondRollOnQuality->getPreconditionsSum()
                    || $rollOnQuality->getRoll()->getValue() !== $secondRollOnQuality->getRoll()->getValue()
                    || $rollOnQuality->getRoll()->getRolledNumbers() !== $secondRollOnQuality->getRoll()->getRolledNumbers()
                ) {
                    throw new Exceptions\RollOnQualityHasToBeTheSame(
                        'Expected same roll of quality for every roll on success, got one with '
                        . $this->describeRollOnQuality($rollOnQuality)
                        . ' and another with ' . $this->describeRollOnQuality($simpleRollOnSuccess->getRollOnQuality())
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
     * @param array|SimpleRollOnSuccess[] $simpleRollsOnSuccess
     * @return array|SimpleRollOnSuccess[]
     */
    private function sortByDifficultyDescending(array $simpleRollsOnSuccess)
    {
        usort($simpleRollsOnSuccess, function (SimpleRollOnSuccess $simpleRollOnSuccess, SimpleRollOnSuccess $anotherSimpleRollOnSuccess) {
            if ($simpleRollOnSuccess->getDifficulty() < $anotherSimpleRollOnSuccess->getDifficulty()) {
                return 1; // with lesser difficulty on top (descending order)
            } else {
                /** equation will never happen, @see \DrdPlus\RollsOn\ExtendedRollOnSuccess::guardUniqueDifficulties */
                return -1; // with greater difficulty on bottom (descending order)
            }
        });

        return $simpleRollsOnSuccess;
    }

    /**
     * @param array|SimpleRollOnSuccess[] $simpleRollsOnSuccess
     * @return RollOnQuality
     */
    private function grabRollOnQuality(array $simpleRollsOnSuccess)
    {
        /** @var SimpleRollOnSuccess $simpleRollOnSuccess */
        $simpleRollOnSuccess = current($simpleRollsOnSuccess);

        return $simpleRollOnSuccess->getRollOnQuality();
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
        $resultOfRollOnSuccess = $this->getResultOfRollOnSuccess();
        if ($resultOfRollOnSuccess) {
            return true;
        }

        return false;
    }

    /**
     * @return bool|SimpleRollOnSuccess
     */
    protected function getResultOfRollOnSuccess()
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
        $resultRollOnSuccess = $this->getResultOfRollOnSuccess();
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