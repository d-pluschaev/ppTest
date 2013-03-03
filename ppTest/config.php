<?php

/**
 * Application config
 */

$cfg = array(
    'precision' => 3, // precision factor

    'php_time_limit' => 120,
    'php_display_errors' => 1,
    'php_error_reporting' => E_ALL,

    'default_annotations' => array(
        'test_count' => 1000,
        'external_loop_count' => 100,
    ),

    'cli' => array(
        'max_console_width' => 113,
    ),
);
