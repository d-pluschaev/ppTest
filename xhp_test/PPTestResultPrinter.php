<?php

/**
 * Abstract class for any result printer
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */
abstract class PPTestResultPrinter
{
    abstract public function startTest();

    abstract public function endTest();

    abstract public function testTitle(array $data);

    abstract public function testCode($code);

    abstract public function testResults(array $data, $handler);

    abstract public function testMetrics(array $data);

    abstract public function matchResults($matchFlag);
}
