<?php

/**
 * Entry point for CLI test runner
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */

require_once 'xhp_test/bootstrap.php';
require_once 'xhp_test/printers/PPTestTestPrinterPrintTestForCli.php';


$testCases = glob(__DIR__ . '/tests/*.php');

foreach ($testCases as $testCaseFile) {

    $testCase = new PPTestCase($testCaseFile, new PPTestPrinterPrintTestForCli());

    if (!$testCase->markedAsSkipped()) {

        $testCase->execute();
    }
}

