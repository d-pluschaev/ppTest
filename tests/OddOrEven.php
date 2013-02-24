<?php

/**
 * @description Утверждение: определение чётности числа по его младшему биту является самым быстрым способом<br/>
 * Тест: Определение чётности числа 2-мя способами: по остатку деления и по младшему биту.<br/>
 * Вывод: в PHP оба способа работают одинаково по времени
 * @skip true
 */
class XHPTestCaseOddOrEven extends XHPTestClass
{
    /**
     * @description <b>Остаток деления</b>
     * @result_handler var_dump
     */
    public function testIf()
    {
        $arr = array(1,2,3,4,5,6,7,8,9,0);
        $res = array();
        foreach($arr as $x){
            $x += 200000000;
            $isEven = $x % 2 == 0;
            if($isEven){
                $res[]=$x;
            }
        }

        return $res;
    }

    /**
     * @description <b>Младший бит</b>
     * @result_handler var_dump
     */
    public function testIf1()
    {
        $arr = array(1,2,3,4,5,6,7,8,9,0);
        $res = array();
        foreach($arr as $x){
            $x += 200000000;
            $isEven = ($x & 1) == 0;
            if($isEven){
                $res[]=$x;
            }
        }

        return $res;
    }
}
