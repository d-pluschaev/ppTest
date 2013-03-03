<?php

/**
 * @description Сравнение скорости доступа к свойствам и методам объекта через Пыхо Магию<br/>
 * Вывод: дополнительные вызовы магических __get, __set, __call увеличивают время на доступ/вызов.<br/>
 * Для get на 30%, для set на 40%, для call на 60%.
 * @skip true
 */
class PPTestCaseMagicMethods extends PPTestClass
{
    public $testClass;

    public function setUp()
    {
        $this->testClass = new PHPMagicTestClass();
    }

    /**
     * @description Получаем значение public свойства
     * @result_handler var_dump
     */
    public function testAccessToPublicProperty()
    {
        return $this->testClass->publicProperty;
    }

    /**
     * @description Получаем значение private свойства, используя __get
     * @result_handler var_dump
     */
    public function testAccessToPrivateProperty()
    {
        return $this->testClass->privateProperty;
    }

    /**
     * @description Присваиваем значение public свойству
     * @result_handler var_dump
     */
    public function testSetPublicProperty()
    {
        $this->testClass->publicProperty = 'public property updated';
    }

    /**
     * @description Присваиваем значение private свойству, используя __set
     * @result_handler var_dump
     */
    public function testSetPrivateProperty()
    {
        $this->testClass->privateProperty = 'private property updated';
    }

    /**
     * @description Вызываем public метод
     * @result_handler var_dump
     */
    public function testCallPublicMethod()
    {
        return $this->testClass->publicMethod();
    }

    /**
     * @description Вызываем private метод, используя __call
     * @result_handler var_dump
     */
    public function testCallPrivateMethod()
    {
        return $this->testClass->privateMethod();
    }
}

class PHPMagicTestClass
{
    public $publicProperty = 'public property';

    private $privateProperty = 'private property';

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __call($name, $value)
    {
        return $this->$name();
    }

    public function publicMethod()
    {
        return 'public method';
    }

    private function privateMethod()
    {
        return 'private method';
    }
}
