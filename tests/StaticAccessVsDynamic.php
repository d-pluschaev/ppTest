<?php

/**
 * @description Доступ к <b>статическим</b> и <b>динамическим</b> свойствам и методам</br>
 * Никаких различий в скорости нет.
 *
 * @skip true
 */
class PPTestCaseStaticAccessVsDynamic extends PPTestClass
{
    private $object;

    public function setUpBeforeClass()
    {
        $this->object = new StaticAccessVsDynamicTestClass();
    }

    /**
     * @description Access to <b>static</b> property
     */
    public function testAccessToStaticProperty()
    {
        $x = StaticAccessVsDynamicTestClass::$staticProperty;
    }

    /**
     * @description Access to <b>dynamic</b> property
     */
    public function testAccessToDynamicProperty()
    {
        $x = $this->object->dynamicProperty;
    }

    /**
     * @description Access to <b>static</b> method
     */
    public function testAccessToStaticMethod()
    {
        $x = StaticAccessVsDynamicTestClass::staticMethod();
    }

    /**
     * @description Access to <b>dynamic</b> method
     */
    public function testAccessToDynamicMethod()
    {
        $x = $this->object->dynamicMethod();
    }
}

class StaticAccessVsDynamicTestClass
{
    public $dynamicProperty = 0;

    public static $staticProperty = 1;

    public static function staticMethod()
    {
        return 2;
    }

    public function dynamicMethod()
    {
        return 3;
    }
}
