<?php

/**
 * Class prints HTML test results
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */
class PPTestPrinterPrintTestAsHTMLCell extends PPTestResultPrinter
{
    public function startCase(PPTestCase $testCase)
    {
        echo '<tr/><tr class="title"><td colspan="' . sizeof($testCase->methods) . '">'
            . $testCase->getClassDescription() . '</td></tr><tr>';
    }

    public function endCase(PPTestCase $testCase)
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
            '<div class="title"><span class="meta">Test #' . ($data['index'] + 1) . ':</span> '
            . $data['description']
            . '</div>';
    }

    public function testCode($code)
    {
        echo $this->highlight($code);
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

        echo '<div class="result">' . (!empty($res) ? "Result: <pre>" . $this->highlight($res) . '</pre>' : '');
    }

    public function testMetrics(array $data)
    {
        $metrics = array(
            'title' => $data['description'],
            'timer' => round($data['timer'] * 1000 * 1000),
            'calls' => intval($data['calls']),
        );

        echo "Average microseconds: <b>{$metrics['timer']}</b><br/>"
            . "Function calls: <b>{$metrics['calls']}</b><br/>";
    }

    public function matchResults($matchFlag)
    {
        switch ($matchFlag) {
            case 1:
                $label = "result is the same as previous";
                $color = 'green';
                break;
            case 0:
                $label = "first result";
                $color = '#777';
                break;
            default:
                $label = "doesn't match";
                $color = 'red';
                break;
        }

        //echo "<div><sup style=\"color:{$color}\">{$label}</sup></div></div>";
    }

    protected function highlight($txt)
    {
        $result = highlight_string("<?$txt", true);
        return substr_replace($result, '', strpos($result, '&lt;?'), 5);
    }

    protected function report(array $data)
    {
        if (!empty($data)) {
            // sort
            usort($data, function ($a, $b) {
                return $a['timer'] > $b['timer'];
            });

            // define graph
            $graphValues = array(
                'timer' => array(),
            );

            // calculate minimal values
            foreach ($graphValues as $gName => &$gData) {
                $gData['min'] = PHP_INT_MAX;
                foreach ($data as $row) {
                    $gData['min'] = $gData['min'] < $row[$gName] ? $gData['min'] : $row[$gName];
                }
            }
            unset($gData);

            $table = array();
            $maxGraphWidth = 200;
            $zoomFactor = 2;

            foreach ($data as $index => $row) {

                // calculate offsets
                $maxOffset = -PHP_INT_MAX;
                foreach ($graphValues as $gName => &$gData) {
                    $gData['offset'] = round(((($row[$gName] - $gData['min']) / $gData['min']) * 100), 2);
                    $maxOffset = $maxOffset < $gData['offset'] ? $gData['offset'] : $maxOffset;
                }
                unset($gData);

                // calculate zoom factor
                if (($maxOffset * $zoomFactor) > $maxGraphWidth) {
                    $zoomFactor = $maxGraphWidth / $maxOffset;
                }

                // fill the data
                $data[$index]['timer'] = round($row['timer'] * 1000 * 1000, 2);
                foreach ($graphValues as $gName => $gData) {
                    $data[$index]['offset_' . $gName] = $gData['offset'];
                }
            }

            foreach ($data as $index => $row) {
                $graph = $index
                    ? '<div class="cmp_graph">'

                        . '<div class="wrap"><div class="timer" style="width:'
                        . round($row['offset_timer'] * $zoomFactor) . 'px"></div></div>'
                        . '<div class="percentage">' . $row['offset_timer'] . ' %</div>'

                        . '</div><div class="dashed_border"></div>'
                    : '<div class="best_res">The best result</div>';

                $table[] = array(
                    'Comparing the results' => $graph,
                    'Title' => ($row['index'] + 1) . ': ' . $row['description'],
                    'Func. calls' => $row['calls'],
                    'Microseconds' => $row['timer'],
                    'Slower on %' => $row['offset_timer'],
                    'Tests performed' => $row['non_xhp_tests_total']
                        . " ({$row['external_loop_count']} x {$row['test_count']})",
                );
            }

            echo '<tr><td colspan="42">';
            echo '<table class="report" cellpadding="0" cellspacing="0"><thead>';
            foreach ($table[0] as $column => $value) {
                echo "<th>$column</th>";
            }
            echo '<thead><tbody>';

            foreach ($table as $row) {
                echo '<tr>';
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo '</tr>';
            }

            echo '</tbody></table></td></tr>';
        }
    }

}
