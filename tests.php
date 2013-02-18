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


$testCase = new XHPTestCase('tests/Tasks1.php', new XHPPrinterPrintTestAsHTMLCell());
$testCase->execute();

?>
</tr></table>

</body>
</html>
