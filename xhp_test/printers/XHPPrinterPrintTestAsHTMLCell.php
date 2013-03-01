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
        $this->report($testCase->data);
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

    protected function highlight($txt)
    {
        $result = highlight_string("<?$txt", true);
        return substr_replace($result, '', strpos($result, '&lt;?'), 5);
    }

    protected function report(array $data)
    {
        if(!empty($data)){
            usort($data, function($a,$b){return $a['wt']>$b['wt'];});

            $minWt=$minTimer=PHP_INT_MAX;
            $maxWt=$maxTimer=-PHP_INT_MAX;
            foreach($data as $row){
                $minWt = $minWt < $row['wt'] ? $minWt : $row['wt'];
                $minTimer = $minTimer < $row['timer'] ? $minTimer : $row['timer'];
                $maxWt = $maxWt > $row['wt'] ? $maxWt : $row['wt'];
                $maxTimer = $maxTimer > $row['timer'] ? $maxTimer : $row['timer'];
            }

            $table = array();

            // graph zoom
            $offsetWt=($maxWt-$minWt)/$maxWt;
            $offsetTimer=($maxTimer-$minTimer)/$maxTimer;
            $maxOffset=$offsetWt> $offsetTimer ? $offsetWt : $offsetTimer;
            $maxGraphWidth=200;
            $zoomFactor=1;
            if($maxOffset > $maxGraphWidth){
                $zoomFactor=$maxGraphWidth/$maxOffset;
            }

            foreach($data as $row){
                $wt=round($row['wt'],2);
                $timer=round($row['timer'] * 1000 * 1000,2);

                $greaterThanMinWt = round(100-(($minWt/$row['wt'])*100),2);
                $greaterThanMinTimer = round(100-(($minTimer/$row['timer'])*100),2);

                $graph='<div class="cmp_graph" style="width:'.$maxGraphWidth.'px">';

                $graph.='<div class="wrap"><div class="wt" style="width:'
                    .round($greaterThanMinWt * $maxGraphWidth/100 * $zoomFactor).'px"></div></div>';
                $graph.='<div class="wrap"><div class="timer" style="width:'
                    .round($greaterThanMinTimer * $maxGraphWidth/100 * $zoomFactor).'px"></div></div>';
                $graph.='</div>';

                $table[]=array(
                    'Graph'=>$graph,
                    'XHP Time'=>$wt,
                    'XHP Time %'=>$greaterThanMinWt,
                    'PHP Time'=>$timer,
                    'PHP Time %'=>$greaterThanMinTimer,
                    'Title'=>$row['description'],
                );
            }

            echo '<tr><td colspan="42"><div>Report:</div>';
            echo '<table class="report"><thead>';
            foreach ($table[0] as $column=>$value){
                echo "<th>$column</th>";
            }
            echo '<thead><tbody>';


            foreach ($table as $row){
                echo '<tr>';
                foreach($row as $value){
                    echo "<td>$value</td>";
                }
                echo '</tr>';
            }
            //print_r($table);


            echo '</tbody></table></td></tr>';
        }
    }

}
