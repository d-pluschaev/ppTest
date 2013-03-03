<?php

/**
 * @description <b>in_array</b>, <b>array_search</b>, <b>array_diff</b><br/>
 * На примере задачи: дано массив из 100 элементов и массив из 10-ти элементов.<br/>
 * Найти количество совпадений в двух массивах.
 * @skip true
 */
class PPTestCaseInArrayVsHash extends PPTestClass
{
    public $array;

    public function setUpBeforeClass()
    {
        $sample = array_keys(array_fill(0, 100, 0));
        $this->array = array_combine($sample, $sample);
    }

    /**
     * @description Самый очевидный: <b>array_intersect</b>
     * @result_handler var_dump
     */
    public function testArrayIntersect()
    {
        // $this->array - массив array(1 => 1, 2 => 2, 3 => 3 .... 100 => 100)

        // массив значений для поиска
        $search = array(29, 39, 49, 59, 69, 79, 89, 99, 109, 119);

        // алгоритм
        $matchesCount = sizeof(array_intersect($search, $this->array));

        return $matchesCount;
    }

    /**
     * @description Тоже очевидный: <b>in_array</b>
     * @result_handler var_dump
     */
    public function testInArray()
    {
        // $this->array - массив array(1 => 1, 2 => 2, 3 => 3 .... 100 => 100)

        // массив значений для поиска
        $search = array(29, 39, 49, 59, 69, 79, 89, 99, 109, 119);

        // алгоритм
        $matchesCount = 0;
        foreach ($search as $searchValue) {
            $matchesCount += in_array($searchValue, $this->array);
        }

        return $matchesCount;
    }

    /**
     * @description Гуру стайл: <b>просто хэш</b>
     * @result_handler var_dump
     */
    public function testHashUsingArrayFlip()
    {
        // $this->array - массив array(1 => 1, 2 => 2, 3 => 3 .... 100 => 100)

        // массив значений для поиска
        $search = array(29, 39, 49, 59, 69, 79, 89, 99, 109, 119);

        // алгоритм
        $matchesCount = 0;
        $hash = array_flip($this->array);
        foreach ($search as $searchValue) {
            $matchesCount += isset($hash[$searchValue]);
        }

        return $matchesCount;
    }
//
//    /**
//     * @description Почему <b>array_flip</b>? <b>Foreach</b> же!
//     */
//    public function testForeach()
//    {
//        // создаём массив array(1 => 1, 2 => 2, 3 => 3 .... 100 => 100)
//        $sample = array_keys(array_fill(0, 100, 0));
//        $array = array_combine($sample, $sample);
//
//        // создаём хэш
//        $hash = array();
//        foreach ($array as $k => $v) {
//            $hash[$v] = $k;
//        }
//    }
//
//    /**
//     * @description Потому что <b>array_flip</b> круче
//     */
//    public function testArrayFlip()
//    {
//        // создаём массив array(1 => 1, 2 => 2, 3 => 3 .... 100 => 100)
//        $sample = array_keys(array_fill(0, 100, 0));
//        $array = array_combine($sample, $sample);
//
//        // создаём хэш
//        $hash = array_flip($array);
//    }
}
