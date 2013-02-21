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
            '<div class="title"><span class="meta">Test #' . ($data['index']) . ':</span> '
            . $data['description']
            . " <sup class=\"meta\">({$data['external_tests_quantity']} x {$data['internal_tests_quantity']})</sup>"
            . '</div>';
    }

    public function testCode($code)
    {
        echo '<br/>' . $this->highlight($code) . '<br/>';
    }

    public function testResults(array $data, $handler)
    {
        switch ($handler) {
            case 'var_dump':
                ob_start();
                var_dump($data['result']);
                $res = ob_get_clean();
                break;
            case 'print_r':
                $res = print_r($data['result'], 1);
                break;
            default:
                $res = print_r($data['result'], 1);
                break;
        }

        echo
            (!empty($res) ? "Result: <pre>" . $this->highlight($res) . '</pre><br/>' : '')
            . "Average Microseconds: <b>{$data['wt']}</b><br/>";
        //. "Average CPU: <b>{$data['cpu']}</b><br/>"
        //. "Average Mem. usage: <b>{$data['mu']}</b><br/>";
        //. "Average Memory. usage (peak): <b>{$data['apmu']}</b><br/>";
    }

    public function matchResults($matchFlag)
    {
        echo '<div></div>';
    }

    public function highlight($txt)
    {
        $result = highlight_string("<?$txt", true);
        return substr_replace($result, '', strpos($result, '&lt;?'), 5);
    }

}
