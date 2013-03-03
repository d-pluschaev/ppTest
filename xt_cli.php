<?php

/**
 * Entry point for CLI test runner
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */

require_once 'xhp_test/bootstrap.php';
require_once 'xhp_test/printers/XHPPrinterPrintTestForCli.php';


$testCases = glob(__DIR__ . '/tests/*.php');

foreach ($testCases as $testCaseFile) {

    $testCase = new XHPTestCase($testCaseFile, new XHPPrinterPrintTestForCli());

    if (!$testCase->markedAsSkipped()) {

        $testCase->execute();
    }
}

