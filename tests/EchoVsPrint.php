<?php

/**
 * @description <b>echo</b> vs <b>print</b>: есть сомнение, говорят что print() тупит, врут?<br/>
 * Ответ: врут
 * @skip true
 */
class XHPTestCaseEchoVsPrint extends XHPTestClass
{
    /**
     * @description <b>echo</b>
     */
    public function testEcho()
    {
        ob_start();
        echo 'test';
        ob_end_clean();
    }

    /**
     * @description <b>echo()</b>
     */
    public function testEchoWithBrackets()
    {
        ob_start();
        echo('test');
        ob_end_clean();
    }

    /**
     * @description <b>print</b>
     */
    public function testPrint()
    {
        ob_start();
        print 'test';
        ob_end_clean();
    }

    /**
     * @description <b>print()</b>
     */
    public function testPrintWithBrackets()
    {
        ob_start();
        print('test');
        ob_end_clean();
    }
}
