<?php

use Core\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);

$env = require_once __DIR__ . '/../env.php';
$container = require_once __DIR__ . '/dependency.php';

$routes = require_once __DIR__ . '/../routes.php';
$router = new Router($container, $routes);
$router->setRoutes();