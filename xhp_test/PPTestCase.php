<?php

require_once 'PPTestResultPrinter.php';
require_once 'PPTestClass.php';

/**
 * Class handles a process of test case execution
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */
class PPTestCase
{
    public $results;
    public $testClass;
    public $testObject;
    public $testClassRC;
    public $methods;
    public $printer;
    public $xhpEnabled;
    public $data;

    public function __construct($testFile, PPTestResultPrinter $printer)
    {
        if (is_file($testFile)) {
            $this->source = file($testFile);
            require_once($testFile);
            $this->testClass = 'PPTestCase' . ucfirst(pathinfo($testFile, PATHINFO_FILENAME));
            $this->testObject = new $this->testClass;
            $this->testClassRC = new ReflectionClass($this->testClass);
            $this->methods = $this->getTestMethods();
            $this->printer = $printer;
            $this->xhpEnabled = function_exists('xhprof_enable');
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

        $this->testObject->setUpBeforeClass();

        foreach ($this->methods as $index => $test) {
            $this->printer->startTest();

            // parse annotations
            $annotations = $this->getMethodAnnotations($test);
            $test_count = $this->getTestAnnotation('test_count', $annotations);
            $external_loop_count = $this->getTestAnnotation('external_loop_count', $annotations);

            // calculate precision
            $precision = PPTestApp::cfg('precision');
            $external_loop_count = round($external_loop_count * $precision);

            // fill primary data
            $primaryData = array(
                'index' => $index,
                'description' => isset($annotations['description']) ? $annotations['description'] : $test,
                'test_count' => $test_count,
                'external_loop_count' => $external_loop_count,
                'precision' => $precision,
                'non_xhp_tests_total' => $external_loop_count * $test_count,
            );

            // print test title
            $this->printer->testTitle($primaryData);

            // code
            $func = $this->testClassRC->getMethod($test);
            $start_line = $func->getStartLine() - 1;
            $end_line = $func->getEndLine();
            $length = $end_line - $start_line;
            $code = implode('', array_slice($this->source, $start_line, $length));
            $this->printer->testCode($code);

            // perform test
            $data = $this->doTest($test_count, $external_loop_count, $test);
            $data = array_merge($primaryData, $data);

            // pass results to printer
            $this->printer->testResults(
                $data,
                isset($annotations['result_handler'])
                    ? $annotations['result_handler']
                    : 'print_r'
            );
            $this->results[$index] = serialize($data['result']);

            $this->printer->matchResults($index ? ($this->results[$index] == $this->results[0] ? 1 : -1) : 0);
            $this->printer->testMetrics($data);
            $this->printer->endTest();

            $this->data[] = $data;
        }

        $this->testObject->tearDownAfterClass();

        $this->printer->endCase($this);
    }

    protected function doTest($test_count, $external_loop_count, $task)
    {
        $metrics = array(
            'timer' => array(),
        );

        $this->testObject->setUp();

        // run test
        for ($j = 0; $j < $external_loop_count; $j++) {
            $timer = microtime(1);
            for ($i = 0; $i < $test_count; $i++) {
                $result = call_user_func_array(array($this->testObject, $task), array());
            }
            $timer2 = microtime(1);
            $metrics['timer'][] = $timer2 - $timer;
        }

        // calculate averages
        foreach ($metrics as &$metric) {
            if (sizeof($metric) > 3) {
                // sort
                sort($metric);
                // remove top 20%
                $metric = array_slice($metric, 0, -round(sizeof($metric) / 20));
                // remove bottom 20%
                $metric = array_slice($metric, round(sizeof($metric) / 20));
                // calc averages
                $metric = array_sum($metric) / sizeof($metric);
            } else {
                $metric = array_sum($metric) / sizeof($metric);
            }
        }
        unset($metric);

        $metrics['calls'] = 'n/a';
        // collect XHP data
        if ($this->xhpEnabled) {
            xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
            call_user_func_array(array($this->testObject, $task), array());
            $xhp = xhprof_disable();
            $metrics['calls'] = $this->xhpCollectFunctionCalls($xhp, $task);
        }

        $metrics['result'] = $result;

        $this->testObject->tearDown();

        return $metrics;
    }

    protected function getTestAnnotation($key, array $annotations)
    {
        return isset($annotations[$key])
            ? $annotations[$key]
            : PPTestApp::cfg('default_annotations', $key);
    }

    protected function xhpCollectFunctionCalls(array $xhpDump, $task)
    {
        $ownCalls = array(
            'main()' => 1,
            'main()==>xhprof_disable' => 1,
            'main()==>call_user_func_array' => 1,
            "call_user_func_array==>{$this->testClass}::$task" => 1,
        );
        $calls = 0;
        foreach ($xhpDump as $call => $data) {
            if (!isset($ownCalls[$call])) {
                $calls += $data['ct'];
            }
        }
        return $calls;
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

