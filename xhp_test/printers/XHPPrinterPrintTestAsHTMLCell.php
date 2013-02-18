<?php

class XHPPrinterPrintTestAsHTMLCell extends XHPTestResultPrinter
{
    public function startTest()
    {
        echo '<td>';
    }

    public function endTest()
    {
        echo '</td>';
    }

    public function testTitle(array $data)
    {
        echo
            '<div class="title">Test #' . ($data['index']) . ': '
            . $data['description']
            . " ({$data['external_tests_quantity']} x {$data['internal_tests_quantity']})"
            . '</div>';
    }

    public function testCode($code)
    {
        echo '<br/>'.$this->highlight($code).'<br/>';
    }

    public function testResults(array $data)
    {
        echo
            "Result: <pre>".$this->highlight(print_r($data['result'],1)).'</pre><br/>'
            . "Average Microseconds: <b>{$data['awt']}</b><br/>"
            . "Average CPU: <b>{$data['acpu']}</b><br/>"
            . "Average Mem. usage: <b>{$data['amu']}</b><br/>";
            //. "Average Memory. usage (peak): <b>{$data['apmu']}</b><br/>";
    }

    public function highlight($txt)
    {
        $result = highlight_string("<?$txt", true);
        return substr_replace($result, '', strpos($result, '&lt;?'), 5);
    }

}
