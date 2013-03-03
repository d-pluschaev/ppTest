<?php

/**
 * @description доступ к различным видам переменных<br/>
 * Вывод:<br/>
 * 1. Получение значения глобальной переменной через массив $GLOBALS почти в 2 раза медленнее,
 * чем предварительное подключение переменной в конcтрукции <b>global</b><br/>
 * 2. Получение значения свойства объекта почти в 3 раза медленнее получение значения локальной переменной
 *
 * @skip true
 */
class XHPTestCaseGlobalVars extends XHPTestClass
{
    protected $var = 'test';

    public function setUp()
    {
        $GLOBALS['var'] = 'test';
    }

    /**
     * @description Глобальная переменная через <b>$GLOBALS</b>
     * @test_count 100
     * @external_loop_count 10
     */
    public function testGetGlobalVar()
    {
        for ($i = 0; $i < 10000; $i++) {
            $x = $GLOBALS['var'];
        }
        return $x;
    }

    /**
     * @description Глобальная переменная через <b>global</b>
     * @test_count 100
     * @external_loop_count 10
     */
    public function testGetGlobalVar2()
    {
        global $var;
        for ($i = 0; $i < 10000; $i++) {
            $x = $var;
        }
        return $x;
    }

    /**
     * @description <b>свойство объекта</b>
     * @test_count 100
     * @external_loop_count 10
     */
    public function testGetObjectProperty()
    {
        for ($i = 0; $i < 10000; $i++) {
            $x = $this->var;
        }
        return $x;
    }

    /**
     * @description <b>локальная переменная</b>
     * @test_count 100
     * @external_loop_count 10
     */
    public function testGetLocalVar()
    {
        $var = 'test';
        for ($i = 0; $i < 10000; $i++) {
            $x = $var;
        }
        return $x;
    }

}
