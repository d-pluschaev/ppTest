<?php

/**
 * Entry point for HTML test runner
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */

require_once 'pp_test/bootstrap.php';
require_once 'pp_test/printers/PPTestPrinterPrintTestAsHTMLCell.php';

header('Content-Type: text/html; charset=UTF-8');

?>

<!doctype html>
<html itemscope="itemscope" itemtype="http://schema.org/WebPage">
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="ppt_html.css"/>
</head>
<body>

<table class="main" cellspacing="0" cellpadding="0">
<tr>

    <?

    $testCases = glob(__DIR__ . '/tests/*.php');

    foreach ($testCases as $testCaseFile) {

        $testCase = new PPTestCase($testCaseFile, new PPTestPrinterPrintTestAsHTMLCell());

        if (!$testCase->markedAsSkipped()) {
            $testCase->execute();
        }
        ?>

        </tr>

    <? }?>

    </tr></table>

</body>
</html>
