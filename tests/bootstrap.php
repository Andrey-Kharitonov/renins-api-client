<?php

use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

define('DATA_DIR', __DIR__ . '/data'); //Some data for testing

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('ReninsApiTest', __DIR__);

$logStream = new StreamHandler(__DIR__ . '/logs/test-' . date('Ymd-His') . '.htm');
$logStream->setFormatter(new HtmlFormatter());
$loggerCalc = new Logger('test');
$loggerCalc->pushHandler($logStream);
