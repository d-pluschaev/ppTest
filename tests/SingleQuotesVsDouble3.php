<?php

/**
 * @description Кавычки одинарные против двойных, часть 3</br>
 * а в этом случае оказывается что двойные чуть медленнее, зависит от количества конкатенаций</br>
 *
 * @skip true
 */
class XHPTestCaseSingleQuotesVsDouble3 extends XHPTestClass
{
    /**
     * @description <b>Одинарные кавычки</b> и переменная
     */
    public function testSingleQuotesAndVar()
    {
        $v = 1;
        $x = 'переменная $v = ' . $v;
        return $x;
    }

    /**
     * @description <b>Двойные кавычки</b> и переменная
     */
    public function testDoubleQuotesAndVar()
    {
        $v = 1;
        $x = "переменная \$v = $v";
        return $x;
    }
}
