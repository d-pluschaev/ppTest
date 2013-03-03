<?php

/**
 * @description <b>array_map</b>, <b>array_walk</b> vs <b>foreach</b> на примере: возвести в квадрат все элементы массива
 * @skip true
 */
class XHPTestCaseArrayMapAndForeach extends XHPTestClass
{
    /**
     * @description <b>array_map</b> вместе с Closure
     */
    public function testArrayMapVsForeach1()
    {
        $arr = array(1, 2, 3);
        return array_map(function ($x) {
            return $x * $x;
        }, $arr);
    }

    /**
     * @description <b>array_walk</b> вместе с Closure
     */
    public function testArrayMapVsForeach1_5()
    {
        $arr = array(1, 2, 3);
        return array_walk($arr, function (&$item, $key) {
            return $item = $key * $key;
        });
    }

    /**
     * @description <b>foreach</b> с передачей по ссылке
     */
    public function testArrayMapVsForeach2()
    {
        $arr = array(1, 2, 3);
        foreach ($arr as &$x) {
            $x = $x * $x;
        }
        unset($x); // представим что у нас ещё куча кода после этого цикла
        // и как нормальные люди удалим ссылку
        return $arr;
    }

    /**
     * @description <b>foreach</b> и обращение по индексу
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
     * @description <b>for</b> и обращение по индексу
     */
    public function testArrayMapVsForeach4()
    {
        $arr = array(1, 2, 3);
        $size = sizeof($arr);
        for ($i = 0; $i < $size; $i++) {
            $arr[$i] = $arr[$i] * $arr[$i];
        }
        return $arr;
    }
}
