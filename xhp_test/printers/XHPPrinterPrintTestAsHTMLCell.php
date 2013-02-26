<?php

class XHPPrinterPrintTestAsHTMLCell extends XHPTestResultPrinter
{
    public function startCase(XHPTestCase $testCase)
    {
        echo '<tr/><tr class="title"><td colspan="'.sizeof($testCase->methods).'">'
            . $testCase->getClassDescription().'</td></tr><tr>';
    }

    public function endCase(XHPTestCase $testCase)
    {

    }

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
            '<div class="title"><span class="meta">Test #' . ($data['index']+1) . ':</span> '
            . $data['description']
            //. " <sup class=\"meta\">({$data['external_tests_quantity']} x {$data['internal_tests_quantity']})</sup>"
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

        echo '<div class="result">'.(!empty($res) ? "Result: <pre>" . $this->highlight($res) . '</pre><br/>' : '');
    }

    public function testMetrics(array $data)
    {
        $metrics = array(
            'title'=>$data['description'],
            'wt' => round($data['wt'],2),
            'timer' => round($data['timer'] * 1000 * 1000),
       );

        echo "Average microseconds per call (xhprof): <b>{$metrics['wt']}</b><br/>"
            . "Average microseconds per test (php): <b>{$metrics['timer']}</b><br/>"
        ;
        //. "Average CPU: <b>{$data['cpu']}</b><br/>"
        //. "Average Mem. usage: <b>{$data['mu']}</b><br/>";
        //. "Average Memory. usage (peak): <b>{$data['apmu']}</b><br/>";
    }

    public function matchResults($matchFlag)
    {
        switch ($matchFlag){
            case 1:
                $label = "result is the same as previous";
                $color='green';
                break;
            case 0:
                $label = "first result";
                $color='#777';
                break;
            default:
                $label = "doesn't match";
                $color='red';
                break;
        }

        echo "<div><sup style=\"color:{$color}\">{$label}</sup></div></div>";
    }

    public function highlight($txt)
    {
        $result = highlight_string("<?$txt", true);
        return substr_replace($result, '', strpos($result, '&lt;?'), 5);
    }

}
