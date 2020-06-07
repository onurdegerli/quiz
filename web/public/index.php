<?php

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);

$env = require_once __DIR__ . '/../env.php';

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

// set configs
$container->set('config', $env);

// set DB instance
$container->set('db', \Core\DB::getInstance($env['db']['host'], $env['db']['database'], $env['db']['user'], $env['db']['password']));

$routes = require_once __DIR__ . '/../routes.php';
$router = new \Core\Router($container, $routes);
$router->setRoutes();