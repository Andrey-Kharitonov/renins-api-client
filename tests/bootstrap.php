<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

define('DATA_DIR', __DIR__ . '/data'); //Some data for testing

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('ReninsApiTest', __DIR__);

$loggerCalc = new Logger('test');
$loggerCalc->pushHandler(new StreamHandler(__DIR__ . '/logs/calc.log'));
