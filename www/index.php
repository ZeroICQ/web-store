<?php

require_once __DIR__ . '/../vendor/autoload.php';

$URI = $_SERVER['REQUEST_URI'];

$kernel = new MicroKernel();

echo $kernel->handleRequest($URI);

