<?php

/**
 * @description Вопрос: Чем чревато использование промежуточных переменных для удобочитаемости кода?<br/>
 * Ответ: ничем
 * @skip true
 */
class XHPTestCaseTemporaryVars extends XHPTestClass
{
    /**
     * @description Используем промежуточную переменную, например, для удобочитаемости
     * @internal_tests_quantity 10
     */
    public function testWithIntermediateVariable()
    {
        for($i=0;$i<1000;$i++){
            $tmp = str_repeat('x',100500);
            $y = $tmp;
        }
    }

    /**
     * @description Без промежуточной переменной
     * @internal_tests_quantity 10
     */
    public function testWithoutIntermediateVariable()
    {
        for($i=0;$i<1000;$i++){
            $y = str_repeat('x',100500);
        }
    }
}
