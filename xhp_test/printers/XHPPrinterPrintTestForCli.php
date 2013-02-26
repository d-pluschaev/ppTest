<?php

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
        $this->printCliTable($table, XHPTestApp::cfg('cli','max_console_width'));
        echo "\n";
    }

    protected function printCliTable(array $table, $maxWidth)
    {
        // calculate width
        $width = 4;
        $height=0;
        $dynamicWidthColumnCount=0;
        foreach($table as $title=>$col){
            if(isset($col['width'])){
                if($col['width']<strlen($title)){
                    $table[$title]['width']=strlen($title);
                }
                $width+=$table[$title]['width']+3;
            }else{
                $dynamicWidthColumnCount++;
            }
            $height=count($col['cells']) < $height ? $height : count($col['cells']);
        }
        $width-=3;

        $freeWidth=$maxWidth-$width;
        if($freeWidth>0){
            $perColumnDynWidth=floor($freeWidth/$dynamicWidthColumnCount)-3;
            foreach($table as $index=>$col){
                if(!isset($col['width'])){
                    $table[$index]['width']=$perColumnDynWidth+($freeWidth%$dynamicWidthColumnCount);
                    $dynamicWidthColumnCount=1;
                    $width+=$table[$index]['width']+3;
                }
            }
        }else{
            $width=$maxWidth;
        }

        // print head
        echo str_repeat('-',$width)."\n";
        echo '| ';
        $cols=array();
        foreach($table as $colName=>$col){
            if(isset($col['width'])){
                $cols[]=$this->printUsingSpaces($colName,$col['width'],false);
            }
        }
        echo implode(' | ',$cols)." |\n";
        echo str_repeat('-',$width)."\n";

        // process word wrap
        foreach($table as $colTitle=>$col){
            if(!empty($col['wrap']) && isset($col['width'])){
                $cellArray=array();
                foreach($col['cells'] as $cell){
                    $rows = explode("\n",$this->mbWordwrap($cell, $col['width']));
                    foreach($rows as $row){
                        $cellArray[]=$row;
                    }
                }
                $height=count($cellArray) < $height ? $height : count($cellArray);
                $table[$colTitle]['cells']=$cellArray;
            }
        }

        // print body
        for($i=0;$i<$height;$i++){
            $row=array();
            foreach($table as $col){
                if(isset($col['width'])){
                    $content=isset($col['cells'][$i]) ? trim($col['cells'][$i]) : '';
                    $row[]=$this->printUsingSpaces($content,$col['width'],false);
                }
            }
            echo $this->printRow($row);
        }

        // print footer
        echo str_repeat('-',$width)."\n";
    }

    protected function printRow(array $row)
    {
        return '| '.implode(' | ',$row)." |\n";
    }

    protected function printUsingSpaces($text, $size, $right)
    {
        $offset = $size - mb_strlen($text, 'UTF-8');
        $out='';
        if($right){
            if($offset >= 0){
                $out.= str_repeat(' ',$offset);
                $out.= $text;
            }else{
                $out.= '...'.mb_substr($text, 0, $size-3, 'UTF-8');
            }
        }else{
            if($offset >= 0){
                $out.= $text;
                $out.= str_repeat(' ',$offset);
            }else{
                $out.= mb_substr($text, 0, $size-3, 'UTF-8').'...';
            }
        }
        return $out;
    }

    protected function mbWordwrap($str, $width, $break = "\n", $cut = true)
    {
        return preg_replace('#(.{'.$width.'}'. ($cut ? '' : '\s') .')#u', '$1'. $break , $str);
    }

}
