<?php

/**
 * @description <b>foreach</b> vs <b>for</b>. Что быстрее?<br/>
 * На примере создания хэша из массива-таблицы: array(0=>array('id'=>1, 'name'=>'test' .... ) размером 1000x20.<br/>
 * Ключом для хэша будет служить столбец "col10";
 * @skip true
 */
class XHPTestCaseForeachVsFor extends XHPTestClass
{
    private $data;

    public function setUp()
    {
        // populate test array
        $this->data = array();
        for($i=0;$i<1000;$i++){
            $row = array();
            for($j=0;$j<20;$j++){
                $row['col'.$j]="data{$j}:{$i}";
            }
            $this->data[]=$row;
        }
    }

    /**
     * @description <b>foreach</b> по строкам таблицы
     * @result_handler var_dump
     */
    public function testMakeHashUsingForeach()
    {
        $key='col10';

        $hash = array();
        foreach($this->data as $row){
            $hash[$row[$key]] = $row;
        }

        return $this->isCorrectHash($hash);
    }

    /**
     * @description <b>for</b> по строкам таблицы
     * @result_handler var_dump
     */
    public function testMakeHashUsingFor()
    {
        $key='col10';

        $hash = array();
        // никогда в for нельзя втыкать count !!!
        // ибо он вычисляется каждую итерацию
        $length = count($this->data);
        for($i=0;$i<$length;$i++){
            // 2 обращения к свойствам объекта за итерацию
            $hash[$this->data[$i][$key]] = $this->data[$i];
        }

        return $this->isCorrectHash($hash);
    }

    /**
     * @description <b>for</b> по строкам таблицы, стараясь не дёргать свойства объекта
     * @result_handler var_dump
     */
    public function testMakeHashUsingForAndLocalVars()
    {
        $key='col10';

        $hash = array();
        $length = count($this->data);
        $data = &$this->data; // локальная переменная-ссылка на свойство объекта
        for($i=0;$i<$length;$i++){
            $hash[$data[$i][$key]] = $data[$i];
        }

        return $this->isCorrectHash($hash);
    }

    /**
     * @description Трансформация исходного массива c использованием <b>foreach</b>.<br/>
     * Память для хэша не выделяется.
     * @result_handler var_dump
     */
    public function testMakeHashUsingTransformMethod()
    {
        $key='col10';

        $hash = &$this->data; // никакого копирования
        foreach($hash as $index=>$row){
            if($index !== $row[$key]) {
                $hash[$row[$key]] = $row;
                unset($hash[$index]);
            } else {
                break;
            }
        }

        return $this->isCorrectHash($hash);
    }

    private function isCorrectHash($hash)
    {
        return sizeof($hash) && !isset($hash[0]);
    }
}
