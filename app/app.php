<?php

date_default_timezone_set('Europe/Luxembourg');
require_once __DIR__.'/bootstrap.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$app = new Silex\Application();

$env = getenv('APP_ENV') ?: 'dev';
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config/$env.json"));

$app['log'] = new Logger('CHD');
$app['log']->pushHandler(new StreamHandler('log/'.date('Ymd').'.log', Logger::DEBUG));

$app->mount('/', new CHD\Controller\scrapeControllerProvider());

return $app;
