<?php

/**
 * @description Кавычки одинарные против двойных, часть 2</br>
 * это тот случай, когда двойные быстрее одинарных</br>
 *
 * @skip true
 */
class PPTestCaseSingleQuotesVsDouble extends PPTestClass
{
    /**
     * @description <b>Одинарные кавычки</b> и переменные
     * @test_count 100
     */
    public function testSingleQuotesAndVar()
    {
        $v = 1;
        $v2 = 2;
        $v3 = 3;

        for ($i = 0; $i < 100; $i++) {
            $x = $v . ' ' . $v2 . ' ' . $v3 . ' ' . $v3 . ' ' . $v3;
        }
        return $x;
    }

    /**
     * @description <b>Двойные кавычки</b> и переменные
     * @test_count 100
     */
    public function testDoubleQuotesAndVar()
    {
        $v = 1;
        $v2 = 2;
        $v3 = 3;

        for ($i = 0; $i < 100; $i++) {
            $x = "$v $v2 $v3 {$v3} {$v3}";
        }
        return $x;
    }
}
