<?php

/**
 * Class prints pretty CLI table
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */
class CustomCLITable
{
    public function getCLITableAsPlainText(array $table, $maxWidth)
    {
        // calculate width
        $width = 4;
        $height = 0;
        $dynamicWidthColumnCount = 0;
        foreach ($table as $title => $col) {
            if (isset($col['width'])) {
                if ($col['width'] < strlen($title)) {
                    $table[$title]['width'] = strlen($title);
                }
                $width += $table[$title]['width'] + 3;
            } else {
                $dynamicWidthColumnCount++;
            }
            $height = count($col['cells']) < $height ? $height : count($col['cells']);
        }
        $width -= 3;

        $freeWidth = $maxWidth - $width;
        if ($freeWidth > 0) {
            $perColumnDynWidth = floor($freeWidth / $dynamicWidthColumnCount) - 3;
            foreach ($table as $index => $col) {
                if (!isset($col['width'])) {
                    $table[$index]['width'] = $perColumnDynWidth + ($freeWidth % $dynamicWidthColumnCount);
                    $dynamicWidthColumnCount = 1;
                    $width += $table[$index]['width'] + 3;
                }
            }
        } else {
            $width = $maxWidth;
        }

        // word wrap
        $this->processWordWrap($table, $height);

        // print head
        $output = str_repeat('-', $width) . "\n";
        $output .= '| ';
        $cols = array();
        foreach ($table as $colName => $col) {
            if (isset($col['width'])) {
                $cols[] = $this->printUsingSpaces($colName, $col['width'], false);
            }
        }
        $output .= implode(' | ', $cols) . " |\n";
        $output .= str_repeat('-', $width) . "\n";

        // print body
        for ($i = 0; $i < $height; $i++) {
            $row = array();
            foreach ($table as $col) {
                if (isset($col['width'])) {
                    $content = isset($col['cells'][$i]) ? trim($col['cells'][$i]) : '';
                    $row[] = $this->printUsingSpaces($content, $col['width'], false);
                }
            }
            $output .= $this->printRow($row);
        }

        // print footer
        $output .= str_repeat('-', $width) . "\n";

        return $output;
    }

    protected function processWordWrap(&$table, &$height)
    {
        foreach ($table as $colTitle => $col) {
            if (!empty($col['wrap']) && isset($col['width'])) {
                $cellArray = array();
                foreach ($col['cells'] as $cell) {
                    $rows = explode("\n", $this->mbWordwrap($cell, $col['width']));
                    foreach ($rows as $row) {
                        $cellArray[] = $row;
                    }
                }
                $height = count($cellArray) < $height ? $height : count($cellArray);
                $table[$colTitle]['cells'] = $cellArray;
            }
        }
    }

    protected function printRow(array $row)
    {
        return '| ' . implode(' | ', $row) . " |\n";
    }

    protected function printUsingSpaces($text, $size, $right)
    {
        $offset = $size - mb_strlen($text, 'UTF-8');
        $out = '';
        if ($right) {
            if ($offset >= 0) {
                $out .= str_repeat(' ', $offset);
                $out .= $text;
            } else {
                $out .= '...' . mb_substr($text, 0, $size - 3, 'UTF-8');
            }
        } else {
            if ($offset >= 0) {
                $out .= $text;
                $out .= str_repeat(' ', $offset);
            } else {
                $out .= mb_substr($text, 0, $size - 3, 'UTF-8') . '...';
            }
        }
        return $out;
    }

    protected function mbWordwrap($str, $width, $break = "\n", $cut = true)
    {
        return preg_replace('#(.{' . $width . '}' . ($cut ? '' : '\s') . ')#u', '$1' . $break, $str);
    }
}
