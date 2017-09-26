<?php

use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

define('DATA_DIR', __DIR__ . '/data'); //Some data for testing
define('TEMP_DIR', DATA_DIR . '/temp'); //Temp data

define('CLIENT_SYSTEM_NAME', ''); //Soap auth
define('PARTNER_UID', ''); //Soap auth

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('ReninsApiTest', __DIR__);

$logStream = new StreamHandler(__DIR__ . '/logs/test-' . date('Ymd-His') . '.htm');
$logStream->setFormatter(new HtmlFormatter());
$loggerCalc = new Logger('test');
$loggerCalc->pushHandler($logStream);
