<?php

/**
 * @description Вопрос: Чем чревато использование промежуточных переменных для удобочитаемости кода?<br/>
 * Ответ: ничем
 * @skip true
 */
class PPTestCaseTemporaryVars extends PPTestClass
{
    /**
     * @description Используем промежуточную переменную, например, для удобочитаемости
     */
    public function testWithIntermediateVariable()
    {
        $tmp = str_repeat('x', 100500);
        $y = $tmp;
        return sizeof($y);
    }

    /**
     * @description Без промежуточной переменной
     */
    public function testWithoutIntermediateVariable()
    {
        $y = str_repeat('x', 100500);
        return sizeof($y);
    }
}
