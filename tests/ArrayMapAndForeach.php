<?php

/**
 * @description <b>array_map</b> vs <b>foreach</b>
 * @skip true
 */
class XHPTestCaseArrayMapAndForeach
{
    /**
     * @description <b>array_map</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function testArrayMapVsForeach1()
    {
        $arr = array(1, 2, 3);
        return array_map(function ($x) {
            return $x * $x;
        }, $arr);
    }

    /**
     * @description <b>foreach</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function testArrayMapVsForeach2()
    {
        $arr = array(1, 2, 3);
        foreach ($arr as &$x) {
            $x = $x * $x;
        }
        return $arr;
    }

    /**
     * @description Сравнение <b>foreach v.2</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function testArrayMapVsForeach3()
    {
        $arr = array(1, 2, 3);
        foreach ($arr as $i => $x) {
            $arr[$i] = $x * $x;
        }
        return $arr;
    }

    /**
     * @description Сравнение <b>for</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     */
    public function testArrayMapVsForeach4()
    {
        $arr = array(1, 2, 3);
        for ($i = 0; $i < sizeof($arr); $i++) {
            $arr[$i] = $arr[$i] * $arr[$i];
        }
        return $arr;
    }
}
