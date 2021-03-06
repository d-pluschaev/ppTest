<?php

/**
 * @description Вопрос: Влияет ли вид условных конструкций на скорость?<br/>
 * Ответ: да, влияет
 * @skip true
 */
class PPTestCaseConditions extends PPTestClass
{
    /**
     * @description Логика в else
     */
    public function testIfCondition1()
    {
        $x = false;
        if ($x) {
            // ничего
        } else {
            return true;
        }
    }

    /**
     * @description Логика в if
     */
    public function testIfCondition2()
    {
        $x = false;
        if (!$x) {
            return true;
        } else {
            // ничего
        }
    }

    /**
     * @description Тернарный оператор
     */
    public function testIfCondition3()
    {
        $x = false;
        return $x ? null : true;
    }

    /**
     * @description switch
     */
    public function testIfCondition4()
    {
        $x = false;
        switch ($x) {
            case true:
                return null;
                break;
            default:
                return true;
                break;
        }
    }
}
