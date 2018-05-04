<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$URI = $_SERVER['REQUEST_URI'];

$kernel = new MicroKernel();

$request = Request::createFromGlobals();

echo $kernel->handleRequest($request);

