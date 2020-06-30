<?php declare(strict_types=1);

namespace Core\Router;

use Core\Exceptions\RouteException;
use Core\Http\Request;
use DI\Container;

class Router
{
    private const CONTROLLER_PATH = "\App\Controllers\\";

    private Container $container;
    private array $routes = [];

    public function __construct(Container $container, array $routes)
    {
        $this->container = $container;
        $this->routes = $routes;
    }

    public function setRoutes()
    {
        $uri = parse_url($_SERVER['REQUEST_URI']);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $dynamicSlugs = [];
        $selectedRoute = [];

        foreach ($this->routes as $route) {
            if (in_array($requestMethod, $route['methods'])
                && $uri['path'] == $route['route']) {
                $selectedRoute = $route;
                break;
            }
        }

        $uriParts = explode('/', $uri['path']);
        unset($uriParts[0]);
        $uriPartsCount = count($uriParts);

        if (empty($selectedRoute)) {
            foreach ($this->routes as $route) {
                $isMatch = false;
                $routeParts = explode('/', $route['route']);
                unset($routeParts[0]);
                for ($i = 1; $i <= $uriPartsCount; $i++) {
                    if (":" == substr($routeParts[$i], 0, 1)) {
                        $dynamicSlugs[] = $uriParts[$i];
                        $isMatch = true;
                        continue;
                    }

                    if ($routeParts[$i] == $uriParts[$i]) {
                        $isMatch = true;
                        continue;
                    }

                    $isMatch = false;
                    break;
                }

                if (true === $isMatch) {
                    $selectedRoute = $route;
                    break;
                }
            }
        }

        if (empty($selectedRoute)) {
            throw new RouteException('Request not found!', 404);
        }

        $controllerName = self::CONTROLLER_PATH . $selectedRoute['controller'] . 'Controller';
        if (!class_exists($controllerName)) {
            throw new RouteException('Controller not found!', 500);
        }

        $actionName = $selectedRoute['action'] . 'Action';

        $controller = new $controllerName($this->container);

        if (!method_exists($controller, $actionName)) {
            throw new RouteException('Method not found!', 500);
        }

        $request = (new Request())->create();

        $controller->$actionName($request, ...$dynamicSlugs);
    }
}