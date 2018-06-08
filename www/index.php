<?php
error_reporting(E_ALL | E_STRICT);
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$URI = $_SERVER['REQUEST_URI'];

$request = Request::createFromGlobals();

$kernel = new MicroKernel($request);


$response = $kernel->handleRequest($request);

$response->prepare($request);
$response->send();

