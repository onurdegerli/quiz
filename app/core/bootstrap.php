<?php declare(strict_types=1);

namespace Core;

use App\Core\Exceptions\RouteException;
use DI\Container;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Bootstrap
{
    /**
     * @throws RouteException
     */
    public function run(): void
    {
        $this->loadEnv();
        $this->registerErrorHandlers();

        require __DIR__ . '/functions.php';

        $containerBuilder = $this->loadDependencies();
        $this->loadRoutes($containerBuilder);
    }

    private function loadEnv()
    {
        try {
            Dotenv::createMutable(__DIR__ . '/../')->load();
        } catch (InvalidPathException $e) {
            die('Environment file is not set.');
        }
    }

    private function registerErrorHandlers(): void
    {
        error_reporting(E_ALL | ~E_NOTICE);

        if ($_ENV['DEBUG'] === 'true') {
            ini_set('display_errors', '1');
        } else {
            ini_set('display_errors', '0');
        }
    }

    private function loadDependencies(): Container
    {
        return (new Dependency())->run();
    }

    /**
     * @param Container $container
     * @throws RouteException
     */
    private function loadRoutes(Container $container): void
    {
        $routes = require_once __DIR__ . '/../config/routes.php';
        $router = new Router($container, $routes);
        $router->setRoutes();
    }
}