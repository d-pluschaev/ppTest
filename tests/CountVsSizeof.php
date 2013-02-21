<?php

/**
 * @description Массивы: <b>count</b> против <b>sizeof</b>.
 * @skip true
 */
class XHPTestCaseCountVsSizeof
{
    private $array;

    public function __construct()
    {
        $sample = array_keys(array_fill(0, 10000, 0));
        $this->array = array_combine($sample, $sample);
    }

    /**
     * @description <b>count</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     * @result_handler var_dump
     */
    public function testCount()
    {
        for ($i = 0; $i < 100; $i++) {
            $x = count($this->array);
        }
        return $x;
    }

    /**
     * @description <b>count</b>
     * @external_tests_quantity 100
     * @internal_tests_quantity 100
     * @result_handler var_dump
     */
    public function testSizeof()
    {
        for ($i = 0; $i < 100; $i++) {
            $x = sizeof($this->array);
        }
        return $x;
    }
}
