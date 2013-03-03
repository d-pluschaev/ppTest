<?php

/**
 * @description Утверждение: <b>count</b> медленнее <b>sizeof</b> при подсчёте размера массива.<br/>
 * Результат теста: они идентичны по времени выполнения
 * @skip true
 */
class PPTestCaseCountVsSizeof extends PPTestClass
{
    private $array;

    public function setUpBeforeClass()
    {
        $sample = array_keys(array_fill(0, 10000, 0));
        $this->array = array_combine($sample, $sample);
    }

    /**
     * @description <b>count</b>
     * @result_handler var_dump
     */
    public function testCount()
    {
        for ($i = 0; $i < 10; $i++) {
            $x = count($this->array);
        }
        return $x;
    }

    /**
     * @description <b>sizeof</b>
     * @result_handler var_dump
     */
    public function testSizeof()
    {
        for ($i = 0; $i < 10; $i++) {
            $x = sizeof($this->array);
        }
        return $x;
    }
}
