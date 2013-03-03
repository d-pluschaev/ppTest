<?php

/**
 * @description <b>isset</b>, <b>empty</b>, <b>array_key_exists</b> Без комментариев
 * @skip true
 */
class PPTestCaseIssetEmptyArrayKeyExists extends PPTestClass
{
    /**
     * @description <b>isset</b>
     * @result_handler var_dump
     */
    public function testIssetEmptyAndEqual1()
    {
        $arr = array(1, 2, 3);
        return isset($arr[1]);
    }

    /**
     * @description <b>!empty</b>
     * @result_handler var_dump
     */
    public function testIssetEmptyAndEqual2()
    {
        $arr = array(1, 2, 3);
        return !empty($arr[1]);
    }

    /**
     * @description <b>array_key_exists</b>
     * @result_handler var_dump
     */
    public function testIssetEmptyAndEqual3()
    {
        $arr = array(1, 2, 3);
        return array_key_exists(1, $arr);
    }
}
