<?php

/**
 * Main application class, contains some static methods
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */
class PPTestApp
{
    public static $instance;

    protected $config;

    public function __construct(array $config)
    {
        self::$instance = $this;
        $this->config = $config;

        $this->config['cli']['max_console_width'] = $this->detectConsoleWidth()
            ? : $this->config['cli']['max_console_width'];
    }

    public function detectConsoleWidth()
    {
        $cols = exec('tput cols', $out);
        if (!empty($cols)) {
            return intval($cols);
        }
    }

    public static function cfg($k1, $k2 = null)
    {
        if ($k2) {
            return isset(self::$instance->config[$k1][$k2]) ? self::$instance->config[$k1][$k2] : null;
        } else {
            return isset(self::$instance->config[$k1]) ? self::$instance->config[$k1] : null;
        }
    }
}
