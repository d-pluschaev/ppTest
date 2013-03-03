<?php

/**
 * Application bootstrap file
 *
 * @author Dmitri Pluschaev dmitri.pluschaev@gmail.com
 */

require_once 'config.php';

ini_set('display_errors', $cfg['php_display_errors']);
ini_set('error_reporting', $cfg['php_error_reporting']);
set_time_limit($cfg['php_time_limit']);

require_once 'XHPTestApp.php';
require_once 'XHPTestCase.php';

new XHPTestApp($cfg);
