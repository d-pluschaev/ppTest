<?php

/**
 * @description Неинтересный тест: <b>call_user_func_array</b> vs <b>$this->$method()</b> и <b>$func()</b></br>
 * </br>
 *
 * @skip true
 */
class PPTestCaseImpactOfCallUserFunc extends PPTestClass
{
    /**
     * @description <b>call_user_func_array</b> для функции
     */
    public function testCUFA1()
    {
        $func = 'funcForImpactOfCallUserFunc';
        return call_user_func_array($func, array());
    }

    /**
     * @description <b>call_user_func_array</b> для метода
     */
    public function testCUFA2()
    {
        $method = 'method';
        return call_user_func_array(array($this, $method), array());
    }

    /**
     * @description вызов функции
     */
    public function testCallFunc()
    {
        $func = 'funcForImpactOfCallUserFunc';
        return $func();
    }

    /**
     * @description самый простой вызов функции
     */
    public function testCallFunc2()
    {
        $func = 'funcForImpactOfCallUserFunc';
        return funcForImpactOfCallUserFunc();
    }

    /**
     * @description вызов метода
     */
    public function testCallMethod()
    {
        $method = 'method';
        return $this->$method();
    }

    public function method()
    {
    }
}

function funcForImpactOfCallUserFunc()
{
}