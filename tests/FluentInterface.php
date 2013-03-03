<?php

/**
 * @description Вопрос: Fluent Interface - тупит или да? ($x = $obj->getModel()->item(1)->save(5);)<br/>
 * Ответ: НЕТ!
 * @skip true
 */
class PPTestCaseFluentInterface extends PPTestClass
{
    private $obj;

    public function setUp()
    {
        $this->obj = new FluentClass();
    }

    /**
     * @description Обычный Fluent Interface
     */
    public function testRegularFluentInterface()
    {
        $x = $this->obj->getModel()->item(1)->save(5);
        return $x;
    }

    /**
     * @description Попробуем избежать таких вызовов
     */
    public function testAvoidFluentInterface()
    {
        $model = $this->obj->getModel();
        $item = $model->item(1);
        $x = $item->save(5);
        return $x;
    }
}

class FluentClass
{
    public function getModel()
    {
        return $this;
    }

    public function item($x)
    {
        return $this;
    }

    public function save($x)
    {
        return true;
    }
}
