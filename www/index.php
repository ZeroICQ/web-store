<?php

use App\Routing\Router;

require_once './init.php';

$URI = $_SERVER['REQUEST_URI'];

echo Router::handleUri($URI);
