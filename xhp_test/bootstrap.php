<?php

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require_once 'config.php';
require_once 'XHPTestApp.php';
require_once 'XHPTestCase.php';

new XHPTestApp($cfg);


