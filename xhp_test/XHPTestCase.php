<?php

require_once 'XHPTestResultPrinter.php';

class XHPTestCase
{
    public function __construct($testFile, XHPTestResultPrinter $printer)
    {
        if (is_file($testFile)) {
            $this->source = file($testFile);
            require_once($testFile);
            $this->testClass = 'XHPTestCase' . ucfirst(pathinfo($testFile, PATHINFO_FILENAME));
            $this->testObject = new $this->testClass;
            $this->testClassRC = new ReflectionClass($this->testClass);
            $this->methods = get_class_methods($this->testClass);
            $this->printer = $printer;
        } else {
            throw new Exception('Test file not found: ' . $testFile);
        }
    }

    public function execute()
    {
        foreach ($this->methods as $index => $test) {
            $this->printer->startTest();

            // parse annotations
            $annotations = $this->getMethodAnnotations($test);
            $internal_tests_quantity = isset($annotations['internal_tests_quantity'])
                ? $annotations['internal_tests_quantity']
                : 1;
            $external_tests_quantity = isset($annotations['external_tests_quantity'])
                ? $annotations['external_tests_quantity']
                : 1;

            // title
            $this->printer->testTitle(
                array(
                    'index' => $index,
                    'description' => isset($annotations['description']) ? $annotations['description'] : $test,
                    'internal_tests_quantity' => $internal_tests_quantity,
                    'external_tests_quantity' => $external_tests_quantity,
                )
            );

            // code
            $func = $this->testClassRC->getMethod($test);
            $start_line = $func->getStartLine() - 1;
            $end_line = $func->getEndLine();
            $length = $end_line - $start_line;
            $code = implode('', array_slice($this->source, $start_line, $length));
            $this->printer->testCode($code);

            $data = $this->doTest($external_tests_quantity, $internal_tests_quantity, $test);
            $this->printer->testResults($data);

            $this->printer->endTest();
        }
    }

    protected function doTest($etq, $itq, $task)
    {
        $swt = $scpu = $smu = $spmu = array();
        for ($j = 0; $j < $etq; $j++) {
            xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

            for ($i = 0; $i < $itq; $i++) {
                $result = call_user_func_array(array($this->testObject, $task), array());
            }

            $xhp = xhprof_disable();
            $xdata = $xhp["call_user_func_array==>{$this->testClass}::$task"];

            $swt[] = $xdata['wt'] / $itq;
            $scpu[] = $xdata['cpu'] / $itq;
            $smu[] = $xdata['mu'] / $itq;
            $spmu[] = $xdata['pmu'] / $itq;
        }

        // sort
        sort($swt);
        sort($scpu);
        sort($smu);
        sort($spmu);

        // remove top 10%
        $swt = array_slice($swt, 0, -round($etq / 20));
        $scpu = array_slice($scpu, 0, -round($etq / 20));
        $smu = array_slice($smu, 0, -round($etq / 20));
        $spmu = array_slice($spmu, 0, -round($etq / 20));

        // remove bottom 10%
        $swt = array_slice($swt, round($etq / 20));
        $scpu = array_slice($scpu, round($etq / 20));
        $smu = array_slice($smu, round($etq / 20));
        $spmu = array_slice($spmu, round($etq / 20));

        // calc averages
        $awt = round(array_sum($swt) / sizeof($swt), 2);
        $acpu = round(array_sum($scpu) / sizeof($scpu), 2);
        $amu = round(array_sum($smu) / sizeof($smu), 2);
        $apmu = round(array_sum($spmu) / sizeof($spmu), 2);


        return array(
            'awt' => $awt,
            'acpu' => $acpu,
            'amu' => $amu,
            'apmu' => $apmu,
            'result' => $result,
        );
    }

    protected function getMethodAnnotations($method)
    {
        $arr = array_slice(
            explode(
                '@',
                str_replace(
                    array('/**', '*/', '*'),
                    '',
                    $this->testClassRC->getMethod($method)->getDocComment()
                )
            ),
            1
        );
        $kv = array();
        foreach ($arr as $row) {
            $parts = explode(' ', $row, 2);
            if (sizeof($parts) == 2) {
                $kv[trim($parts[0])] = trim($parts[1]);
            }
        }
        return $kv;
    }
}

