<?php

require_once 'CustomCLITable.php';

class XHPPrinterPrintTestForCli extends XHPTestResultPrinter
{
    public function startCase(XHPTestCase $testCase)
    {
        echo str_repeat('_',60) . "\n";
        echo strip_tags($testCase->getClassDescription()) . "\n";
        echo str_repeat('_',60) . "\n";
    }

    public function endCase(XHPTestCase $testCase)
    {
        $this->report($testCase->data);
    }

    public function startTest()
    {
        echo '';
    }

    public function endTest()
    {
        echo str_repeat('_', 60) . "\n";
    }

    public function testTitle(array $data)
    {
        echo 'Test #' . ($data['index']+1) . ': ' . strip_tags($data['description']) . "\n";
    }

    public function testCode($code)
    {
        echo "\n{$code}\n";
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

        echo !empty($res) ? "Result: $res\n" : '';
    }

    public function testMetrics(array $data)
    {
        $metrics = array(
            'title'=>$data['description'],
            'wt' => round($data['wt'],2),
            'timer' => round($data['timer'] * 1000 * 1000),
        );
        echo "Average microseconds per call (xhprof): {$metrics['wt']}\n"
            . "Average microseconds per test (php): {$metrics['timer']}\n";
    }

    public function matchResults($matchFlag)
    {
        switch ($matchFlag){
            case 1:
                $label = "result is the same as previous";
                break;
            case 0:
                $label = "first result";
                break;
            default:
                $label = "doesn't match";
                break;
        }

        echo "[{$label}]\n";
    }

    protected function report(array $data)
    {
        usort($data, function($a,$b){return $a['wt']>$b['wt'];});

        $minWt=$minTimer=PHP_INT_MAX;
        foreach($data as $row){
            $minWt = $minWt < $row['wt'] ? $minWt : $row['wt'];
            $minTimer = $minTimer < $row['timer'] ? $minTimer : $row['timer'];
        }

        $table = array(
            'XHP Time'=>array(
                'width'=>0,
                'cells'=>array(),
            ),
            'XHP Time %'=>array(
                'width'=>0,
                'cells'=>array(),
            ),
            'PHP Time'=>array(
                'width'=>0,
                'cells'=>array(),
            ),
            'PHP Time %'=>array(
                'width'=>0,
                'cells'=>array(),
            ),
            'Title'=>array(
                'cells'=>array(),
                'wrap'=>true,
            ),
        );

        foreach($data as $row){
            $wt=round($row['wt'],2);
            $timer=round($row['timer'] * 1000 * 1000,2);

            $greaterThanMinWt = round(100-(($minWt/$row['wt'])*100),2);
            $greaterThanMinTimer = round(100-(($minTimer/$row['timer'])*100),2);

            $table['XHP Time']['cells'][]=$wt;
            $table['XHP Time %']['cells'][]=$greaterThanMinWt;
            $table['PHP Time']['cells'][]=$timer;
            $table['PHP Time %']['cells'][]=$greaterThanMinTimer;
            $table['Title']['cells'][]=strip_tags($row['description']);
        }

        $cliTable = new CustomCLITable();
        $plainText = $cliTable->getCLITableAsPlainText($table, XHPTestApp::cfg('cli','max_console_width'));

        echo "$plainText\n";
    }
}
