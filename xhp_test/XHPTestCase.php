<?php

require_once 'XHPTestResultPrinter.php';
require_once 'XHPTestClass.php';

class XHPTestCase
{
    protected $results;

    public $data;

    public function __construct($testFile, XHPTestResultPrinter $printer)
    {
        if (is_file($testFile)) {
            if (function_exists('xhprof_enable')) {
                $this->source = file($testFile);
                require_once($testFile);
                $this->testClass = 'XHPTestCase' . ucfirst(pathinfo($testFile, PATHINFO_FILENAME));
                $this->testObject = new $this->testClass;
                $this->testClassRC = new ReflectionClass($this->testClass);
                $this->methods = $this->getTestMethods();
                $this->printer = $printer;
            } else {
                throw new Exception('PHP XHProf extension required');
            }
        } else {
            throw new Exception('Test file not found: ' . $testFile);
        }
    }

    public function getClassDescription()
    {
        $comment = $this->testClassRC->getDocComment();
        $annotations = $this->parseAnnotations($comment);
        return isset($annotations['description']) ? $annotations['description'] : $this->testClass;
    }

    public function markedAsSkipped()
    {
        $comment = $this->testClassRC->getDocComment();
        $annotations = $this->parseAnnotations($comment);
        return isset($annotations['skip']) ? $annotations['skip'] == 'true' : false;
    }

    public function execute()
    {
        $this->printer->startCase($this);

        foreach ($this->methods as $index => $test) {
            $this->printer->startTest();

            // parse annotations
            $annotations = $this->getMethodAnnotations($test);
            $internal_tests_quantity = isset($annotations['internal_tests_quantity'])
                ? $annotations['internal_tests_quantity']
                : 100;
            $external_tests_quantity = isset($annotations['external_tests_quantity'])
                ? $annotations['external_tests_quantity']
                : 100;

            // title
            $primaryData=array(
                'index' => $index,
                'description' => isset($annotations['description']) ? $annotations['description'] : $test,
                'internal_tests_quantity' => $internal_tests_quantity,
                'external_tests_quantity' => $external_tests_quantity,
            );
            $this->printer->testTitle($primaryData);

            // code
            $func = $this->testClassRC->getMethod($test);
            $start_line = $func->getStartLine() - 1;
            $end_line = $func->getEndLine();
            $length = $end_line - $start_line;
            $code = implode('', array_slice($this->source, $start_line, $length));
            $this->printer->testCode($code);

            $data = $this->doTest($external_tests_quantity, $internal_tests_quantity, $test);
            $data = array_merge($primaryData, $data);

            $this->printer->testResults(
                $data,
                isset($annotations['result_handler'])
                    ? $annotations['result_handler']
                    : 'print_r'
            );
            $this->results[$index] = serialize($data['result']);

            $this->printer->matchResults($index ? ($data['result'] == $this->results[0] ? 1 : -1) : 0);
            $this->printer->testMetrics($data);
            $this->printer->endTest();

            $this->data[] = $data;
        }

        $this->printer->endCase($this);
    }

    protected function doTest($etq, $itq, $task)
    {
        $metrics = array(
            'wt' => array(),
            'cpu' => array(),
            'mu' => array(),
            'pmu' => array(),
            'timer' => array(),
        );

        $this->testObject->setUp();

        for ($j = 0; $j < $etq; $j++) {
            $timer = microtime(1);

            xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

            for ($i = 0; $i < $itq; $i++) {
                $result = call_user_func_array(array($this->testObject, $task), array());
                usleep(100);
            }

            $xhp = xhprof_disable();
            $xdata = $xhp["call_user_func_array==>{$this->testClass}::$task"];

            $metrics['wt'][] = $xdata['wt'] / $itq;
            $metrics['cpu'][] = $xdata['cpu'] / $itq;
            $metrics['mu'][] = $xdata['mu'] / $itq;
            $metrics['pmu'][] = $xdata['pmu'] / $itq;
            $metrics['timer'][] = microtime(1) - $timer;

            usleep(10);
        }

        foreach ($metrics as &$metric) {
            if (sizeof($metric) > 3) {
                // sort
                sort($metric);
                // remove top 20%
                $metric = array_slice($metric, 0, -round($etq / 20));
                // remove bottom 20%
                $metric = array_slice($metric, round($etq / 20));
                // calc averages
                $metric = array_sum($metric) / sizeof($metric);
            } else {
                $metric = array_sum($metric) / sizeof($metric);
            }
        }

        $metrics['result'] = $result;

        $this->testObject->tearDown();

        return $metrics;
    }

    protected function getMethodAnnotations($method)
    {
        return $this->parseAnnotations($this->testClassRC->getMethod($method)->getDocComment());
    }

    protected function parseAnnotations($text)
    {
        $arr = array_slice(explode('@', str_replace(array('/**', '*/', '*'), '', $text)), 1);
        $kv = array();
        foreach ($arr as $row) {
            $parts = explode(' ', $row, 2);
            if (sizeof($parts) == 2) {
                $kv[trim($parts[0])] = trim($parts[1]);
            }
        }
        return $kv;
    }

    protected function getTestMethods()
    {
        $methods = get_class_methods($this->testClass);
        $tests = array();
        foreach (get_class_methods($this->testClass) as $method) {
            if (strtolower(substr($method, 0, 4)) == 'test') {
                $tests[] = $method;
            }
        }
        return $tests;
    }
}

