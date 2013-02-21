<?php

abstract class XHPTestResultPrinter
{
    abstract public function startTest();

    abstract public function endTest();

    abstract public function testTitle(array $data);

    abstract public function testCode($code);

    abstract public function testResults(array $data, $handler);

    abstract public function matchResults($matchFlag);
}
