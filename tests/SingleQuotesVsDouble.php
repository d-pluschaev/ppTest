<?php

/**
 * @description Кавычки одинарные против двойных
 * @skip true
 */
class XHPTestCaseSingleQuotesVsDouble extends XHPTestClass
{
    /**
     * @description <b>Одинарные кавычки</b>
     */
    public function testSingleQuotes()
    {
        $v = 28;
        $x = 'переменная $v = ' . $v;
        return $x;
    }

    /**
     * @description <b>Двойные кавычки</b>
     */
    public function testDoubleQuotes()
    {
        $v = 28;
        $x = "переменная \$v = $v";
        return $x;
    }
}
