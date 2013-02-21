<?php


ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

header('Content-Type: text/html; charset=UTF-8');


require_once 'xhp_test/XHPTestCase.php';
require_once 'xhp_test/printers/XHPPrinterPrintTestAsHTMLCell.php';



?>

<!doctype html>
<html itemscope="itemscope" itemtype="http://schema.org/WebPage">
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="tests.css" />
</head>
<body>

<table class="main"><tr>

<?

    $testCases = glob(__DIR__.'/tests/*.php');

    foreach($testCases as $testCaseFile){

        $testCase = new XHPTestCase($testCaseFile, new XHPPrinterPrintTestAsHTMLCell());

        if (!$testCase->markedAsSkipped()) {

            echo '<tr/><tr class="title"><td colspan="'.sizeof($testCase->methods).'">'
                . $testCase->getClassDescription().'</td></tr><tr>';

            $testCase->execute();

        }
        ?>

        </tr>

    <?}?>

</tr></table>

</body>
</html>
