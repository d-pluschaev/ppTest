<?php

require_once 'CustomCLITable.php';

/**
 * Class prints CLI test results
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */
class PPTestPrinterPrintTestForCli extends PPTestResultPrinter
{
    public function startCase(PPTestCase $testCase)
    {
        echo str_repeat('_', PPTestApp::cfg('cli', 'max_console_width')) . "\n";
        echo strip_tags($testCase->getClassDescription()) . "\n";
        echo str_repeat('_', PPTestApp::cfg('cli', 'max_console_width')) . "\n";
    }

    public function endCase(PPTestCase $testCase)
    {
        $this->report($testCase->data);
    }

    public function startTest()
    {
        echo '';
    }

    public function endTest()
    {
        echo str_repeat('_', PPTestApp::cfg('cli', 'max_console_width')) . "\n";
    }

    public function testTitle(array $data)
    {
        echo 'Test #' . ($data['index'] + 1) . ': ' . strip_tags($data['description']) . "\n";
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
            'title' => $data['description'],
            'timer' => round($data['timer'] * 1000 * 1000),
        );
        echo "Average microseconds: {$metrics['timer']}\n";
    }

    public function matchResults($matchFlag)
    {
        switch ($matchFlag) {
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

        //echo "[{$label}]\n";
    }

    protected function report(array $data)
    {
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

        $table = array(
            'Microseconds' => array(
                'width' => 0,
                'cells' => array(),
            ),
            'Slower on %' => array(
                'width' => 0,
                'cells' => array(),
            ),
            'Func. calls' => array(
                'width' => 0,
                'cells' => array(),
            ),
            'Title' => array(
                'cells' => array(),
                'wrap' => true,
            ),
        );

        foreach ($data as $row) {
            // calculate offsets
            foreach ($graphValues as $gName => &$gData) {
                $gData['offset'] = round(((($row[$gName] - $gData['min']) / $gData['min']) * 100), 2);
            }
            unset($gData);

            $table['Microseconds']['cells'][] = round($row['timer'] * 1000 * 1000, 2);
            $table['Slower on %']['cells'][] = $graphValues['timer']['offset'];
            $table['Title']['cells'][] = strip_tags($row['description']);
            $table['Func. calls']['cells'][] = $row['calls'];
        }

        $cliTable = new CustomCLITable();
        $plainText = $cliTable->getCLITableAsPlainText($table, PPTestApp::cfg('cli', 'max_console_width'));

        echo "$plainText\n";
    }
}
