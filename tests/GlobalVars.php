<?php

/**
 * @description доступ к различным видам переменных<br/>
 * Вывод:<br/>
 * 1. Получение значения глобальной переменной через массив $GLOBALS в 2 раза медленнее,
 * чем предварительное подключение переменной в конcтрукции <b>global</b><br/>
 * 2. Получение значения свойства объекта почти в 4 раза медленнее получение значения локальной переменной
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
     * @internal_tests_quantity 1
     */
    public function testGetGlobalVar()
    {
        for($i=0;$i<100000;$i++){
            $x = $GLOBALS['var'];
        }
        return $x;
    }

    /**
     * @description Глобальная переменная через <b>global</b>
     * @internal_tests_quantity 1
     */
    public function testGetGlobalVar2()
    {
        global $var;
        for($i=0;$i<100000;$i++){
            $x = $var;
        }
        return $x;
    }

    /**
     * @description <b>свойство объекта</b>
     * @internal_tests_quantity 1
     */
    public function testGetObjectProperty()
    {
        for($i=0;$i<100000;$i++){
            $x = $this->var;
        }
        return $x;
    }

    /**
     * @description <b>локальная переменная</b>
     * @internal_tests_quantity 1
     */
    public function testGetLocalVar()
    {
        $var = 'test';
        for($i=0;$i<100000;$i++){
            $x = $var;
        }
        return $x;
    }

}
