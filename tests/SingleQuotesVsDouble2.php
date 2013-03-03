<?php

/**
 * @description Кавычки одинарные против двойных, часть 2</br>
 * разницы между двойными и одинарными нету в случае если не используются переменные</br>
 *
 * @skip true
 */
class XHPTestCaseSingleQuotesVsDouble2 extends XHPTestClass
{
    /**
     * @description <b>Одинарные кавычки</b>
     */
    public function testSingleQuotes()
    {
        $x = 'строка';
        return $x;
    }

    /**
     * @description <b>Двойные кавычки</b>
     */
    public function testDoubleQuotes()
    {
        $x = "строка";
        return $x;
    }
}
