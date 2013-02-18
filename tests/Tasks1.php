<?php

class XHPTestCaseTasks1
{

    /**
     * @description Сравнение <b>array_map</b> и <b>foreach</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function arrayMapVsForeach1()
    {
        $arr=array(1,2,3);
        return array_map(function($x){return $x*$x;},$arr);
    }

    /**
     * @description Сравнение <b>array_map</b> и <b>foreach</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function arrayMapVsForeach2()
    {
        $arr=array(1,2,3);
        foreach($arr as &$x){
            $x=$x*$x;
        }
        return $arr;
    }
}
