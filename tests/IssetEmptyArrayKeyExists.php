<?php

/**
 * @description <b>isset</b>, <b>empty</b>, <b>array_key_exists</b>
 * @skip true
 */
class XHPTestCaseIssetEmptyArrayKeyExists
{
    /**
     * @description <b>isset</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     * @result_handler var_dump
     */
    public function testIssetEmptyAndEqual1()
    {
        $arr = array(1, 2, 3);
        return isset($arr[1]);
    }

    /**
     * @description <b>!empty</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     * @result_handler var_dump
     */
    public function testIssetEmptyAndEqual2()
    {
        $arr = array(1, 2, 3);
        return !empty($arr[1]);
    }

    /**
     * @description <b>array_key_exists</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     * @result_handler var_dump
     */
    public function testIssetEmptyAndEqual3()
    {
        $arr = array(1, 2, 3);
        return array_key_exists(1, $arr);
    }
}
