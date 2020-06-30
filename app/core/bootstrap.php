<?php declare(strict_types=1);

namespace Core;

use Core\Dependency\Dependency;
use Core\Exceptions\ConfigException;
use Core\Exceptions\RouteException;
use Core\Router\Router;
use DI\Container;

/**
 * Class Bootstrap
 * @package Core
 */
class Bootstrap
{
    /**
     * @throws ConfigException
     * @throws RouteException
     */
    public function run(): void
    {
        require __DIR__ . '/Helpers/Helpers.php';

        $this->loadEnv();
        $this->registerErrorHandlers();

        $containerBuilder = $this->loadDependencies();
        $this->loadRoutes($containerBuilder);
    }

    private function loadEnv()
    {
        $envVars = require_once __DIR__ . '/../env.php';
        if (!is_array($envVars) || !$envVars) {
            throw new ConfigException('Environment file not found or empty.');
        }

        foreach ($envVars as $var => $value) {
            $var = strtoupper($var);
            putenv("$var=" . $value);
        }
    }

    private function registerErrorHandlers(): void
    {
        error_reporting(E_ALL | ~E_NOTICE);

        // TODO: a logging service(MONOLOG, etc) might be implemented.
        if (getenv('DEBUG') === true) {
            ini_set('display_errors', '1');
        } else {
            ini_set('display_errors', '0');
        }
        ini_set('display_errors', '1');
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