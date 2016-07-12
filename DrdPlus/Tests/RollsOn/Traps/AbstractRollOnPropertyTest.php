<?php
namespace DrdPlus\Tests\RollsOn\Traps;

use Drd\DiceRoll\Templates\Rollers\Roller2d6DrdPlus;
use DrdPlus\Properties\Base\BaseProperty;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;

abstract class AbstractRollOnPropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $sutClass = $this->getSutClass();
        $propertyClass = $this->getPropertyClass();
        /** @var RollOnQuality $rollOnProperty */
        $rollOnProperty = new $sutClass(
            $property = $propertyClass::getIt($willValue = 123),
            $roll = Roller2d6DrdPlus::getIt()->roll()
        );
        $getProperty = $this->getPropertyGetter();
        self::assertSame($property, $rollOnProperty->$getProperty());
        self::assertSame($willValue, $rollOnProperty->getPreconditionsSum());
        self::assertSame($roll, $rollOnProperty->getRoll());
        $resultValue = $willValue + $roll->getValue();
        self::assertSame($resultValue, $rollOnProperty->getValue());
        self::assertSame((string)$resultValue, (string)$rollOnProperty);
    }

    /**
     * @return string|RollOnQuality
     */
    protected function getSutClass()
    {
        return preg_replace('~[\\\]Tests(.+)Test$~', '$1', static::class);
    }

    /**
     * @return string|BaseProperty
     */
    protected function getPropertyClass()
    {
        $propertyNamespace = (new \ReflectionClass(BaseProperty::class))->getNamespaceName();

        return $propertyNamespace . '\\' . preg_replace('~^.+RollOn(.+)$~', '$1', $this->getSutClass());
    }

    protected function getPropertyGetter()
    {
        $propertyName = preg_replace('~^(?:.+[\\\])?(\w+)$~', '$1', $this->getPropertyClass());

        return 'get' . $propertyName;
    }
}