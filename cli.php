<?php

use Symfony\Component\HttpFoundation\Request;

$app                     = require_once __DIR__.'/app/app.php';
list($_, $method, $path) = $argv;
$request                 = Request::create($path, $method);
$app->run($request);
