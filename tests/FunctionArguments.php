<?php

/**
 * @description Передача аргументов внутрь функций и возврат результата<br/>
 * Выводы:<br/>
 * 1. Количество аргументов незначительно влияет на скорость вызова *<br/>
 * 2. Скорость вызова незначительно зависит от размера передаваемых аргументов и возвращаемого результата *<br/>
 * @skip true
 */
class XHPTestCaseFunctionArguments extends XHPTestClass
{
    public $veryHugeString;

    public function setUp()
    {
        $this->veryHugeString = str_repeat('1', 100500);
    }

    /**
     * @description Вызов без аргументов
     */
    public function testCallWithOneArgument()
    {
        $this->testVoidFunction(1);
    }

    /**
    * @description Вызов c несколькими аргументами
    */
    public function testCallWithArguments()
    {
        $this->testVoidFunction(1,2,3,4,5,6,7,8,9,0);
    }

    /**
     * @description Вызов VOID функции c аргументом размером 100500 байт
     */
    public function testCallWithHugeArgumentPassedByValue()
    {
        $this->testVoidFunction($this->veryHugeString);
    }

    /**
     * @description Вызов функции, возвращающей строку, c аргументом размером 100500 байт
     */
    public function testCallWithHugeArgumentPassedByLink()
    {
        $this->testStringFunction($this->veryHugeString);
    }

    protected function testVoidFunction($x)
    {
        $y = $x . ' ';
    }

    protected function testStringFunction(&$x)
    {
        $y = $x . ' ';
        return $y;
    }
}
