<?php

/**
 * @description Влияние <b>xhprof_enable</b> на вызовы функций/методов<br/>
 * Включённый XHProf увеличивает время вызова функции/метода в среднем на 800% (с 0.25 мкс до 2мкс)
 * - точное значение в diffirence_value<br/>
 * засчёт прокси-функции в PHP extension. Чтобы получить задержку в 1 секунду из-за XHProf, необходимо 571479 вызовов
 * @skip true
 */
class PPTestCaseXHProfImpact extends PPTestClass
{
    private $results = array();

    public function setUp()
    {
        $count = 100000;
        $this->results['Without XHProf'] = array(
            'Regular function' => $this->callTestFunction($count),
            'Internal function' => $this->callTestInternalFunction($count),
            'Method' => $this->callTestMethod($count),
        );
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        $this->results['With XHProf'] = array(
            'Regular function' => $this->callTestFunction($count),
            'Internal function' => $this->callTestInternalFunction($count),
            'Method' => $this->callTestMethod($count),
        );
        xhprof_disable();

        $this->results['XHProf impact percentage'] = array();
        $summary = 0;
        foreach ($this->results['Without XHProf'] as $category => $value) {
            $wXhp = $this->results['With XHProf'][$category];

            $delta = $wXhp - $value;
            $aNX = round($value / $count, 2);
            $aWX = round($wXhp / $count, 2);

            $this->results['XHProf impact percentage'][$category] =
                round($delta / $value * 100) . "% ($aNX => $aWX +" . round($delta / $count, 2) . ')';

            $summary += $delta / $count;
        }
        $this->results['diffirence_value'] = round($summary / count($this->results['Without XHProf']), 3);
    }

    /**
     * @description <b>array_map</b>
     * @test_count 1
     */
    public function testShowResults()
    {
        return $this->results;
    }

    private function callTestFunction($count)
    {
        $timer = microtime(1);
        for ($i = 0; $i < $count; $i++) {
            $x = XHPTestCaseXHProfImpactTestFunc();
        }
        return (microtime(1) - $timer) * 1000 * 1000;
    }

    private function callTestInternalFunction($count)
    {
        $timer = microtime(1);
        for ($i = 0; $i < $count; $i++) {
            $x = trim('1');
        }
        return (microtime(1) - $timer) * 1000 * 1000;
    }

    private function callTestMethod($count)
    {
        $timer = microtime(1);
        for ($i = 0; $i < $count; $i++) {
            $x = $this->callTestMethodMethod();
        }
        return (microtime(1) - $timer) * 1000 * 1000;
    }

    private function callTestMethodMethod()
    {
    }


}

function XHPTestCaseXHProfImpactTestFunc()
{
}
