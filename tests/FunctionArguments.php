<?php

/**
 * @description Передача аргументов внутрь функций и возврат результата<br/>
 * Выводы:<br/>
 * 1. Количество аргументов незначительно влияет на скорость вызова *<br/>
 * 2. Скорость вызова значительно зависит от размера передаваемых аргументов<br/>
 * (даже если они передаются по ссылке) и практически не зависит от возвращаемого<br/>
 * результата
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
        $x = $this->veryHugeString;
        $this->testVoidFunction(1);
    }

    /**
     * @description Вызов c несколькими аргументами
     */
    public function testCallWithArguments()
    {
        $x = $this->veryHugeString;
        $this->testVoidFunction(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
    }

    /**
     * @description Вызов VOID функции c аргументом размером 100500 байт
     */
    public function testCallWithHugeArgumentPassedByValue()
    {
        $x = $this->veryHugeString;
        $this->testVoidFunction($x);
    }

    /**
     * @description Вызов функции, возвращающей строку, c аргументом размером 100500 байт
     */
    public function testCallWithHugeArgument()
    {
        $x = $this->veryHugeString; // это значение НЕ дублируется,
        // ЕСЛИ мы не пытаемся изменить эту переменную.
        $this->testStringFunction($x);
    }

    /**
     * @description Вызов функции, возвращающей строку, c аргументом размером 100500 байт
     */
    public function testCallWithHugeArgumentPassedByLink()
    {
        $x = $this->veryHugeString; // это значение НЕ дублируется,
        // ЕСЛИ мы не пытаемся изменить эту переменную,
        // передача по ссылке расценивается как попытка изменить её.
        $this->testStringFunctionWithLink($this->veryHugeString);
        // Вопрос: если бы аргументом была $this->veryHugeString вместо $x,
        // то разницы в скорости не было бы?
    }

    protected function testVoidFunction($x)
    {
        $y = $x . ' ';
    }

    protected function testStringFunction($x)
    {
        $y = $x . ' ';
        return $y;
    }

    protected function testStringFunctionWithLink(&$x)
    {
        $y = $x . ' ';
        return $y;
    }
}
