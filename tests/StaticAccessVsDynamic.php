<?php

/**
 * @description <b>static</b> and <b>dynamic</b> access to class methods and properties
 * @skip true
 */
class XHPTestCaseStaticAccessVsDynamic
{
    private $object;

    public function __construct()
    {
        $this->object = new StaticAccessVsDynamicTestClass();
    }

    /**
     * @description Access to <b>static</b> property
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function testAccessToStaticProperty()
    {
        $x = StaticAccessVsDynamicTestClass::$staticProperty;
    }

    /**
     * @description Access to <b>dynamic</b> property
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function testAccessToDynamicProperty()
    {
        $x = $this->object->dynamicProperty;
    }

    /**
     * @description Access to <b>static</b> method
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function testAccessToStaticMethod()
    {
        $x = StaticAccessVsDynamicTestClass::staticMethod();
    }

    /**
     * @description Access to <b>dynamic</b> method
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
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
