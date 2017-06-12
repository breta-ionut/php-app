<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

error_reporting(-1);
ini_set('display_errors', 'on');

require __DIR__.'/vendor/autoload.php';

$kernel = new Kernel(getenv('env') ?: 'dev', getenv('debug') ?: true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
