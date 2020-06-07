<?php
namespace Core;

use DI\Container;

class Router
{
    private Container $container;

    private array $routes = [];

    private string $controllerPath = "\App\Controllers\\";

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
                $isMatch = FALSE;
                $routeParts = explode('/', $route['route']);
                unset($routeParts[0]);
                for ($i = 1; $i <= $uriPartsCount; $i++) {
                    if (":" == substr( $routeParts[$i], 0, 1 )) {
                        $dynamicSlug = str_replace(':', '', $routeParts[$i]);
                        $dynamicSlugs[$dynamicSlug] = $uriParts[$i];
                        $isMatch = TRUE;
                    } else {
                        if ($routeParts[$i] == $uriParts[$i]) {
                            $isMatch = TRUE;
                        } else {
                            $isMatch = FALSE;
                            break;
                        }
                    }
                }
    
                if (TRUE === $isMatch) {
                    $selectedRoute = $route;
                    break;
                }
            }
        }

        if (empty($selectedRoute)) {
            throw new \Exception("The request is not found!");
        }

        $request = $this->getRequest();

        $controllerName = $this->controllerPath . $selectedRoute['controller'] . 'Controller';
        $actionName = $selectedRoute['action'] . 'Action';

        $controller = new $controllerName($this->container);
        $controller->$actionName($request, $dynamicSlugs);
    }

    private function getRequest()
    {
        $request = [
            'payload' => $this->getRequestPayload(),
            'get' => $_GET,
            'post' => $_POST,
        ];

        $request = filter_var_array($request, FILTER_SANITIZE_STRING);

        unset($_GET);
        unset($_POST);
        unset($_REQUEST);

        return $request;
    }

    private function getRequestPayload(): array
    {
        $jsonStr = file_get_contents('php://input');
        if (empty($jsonStr)) {
            return [];
        }

        return json_decode($jsonStr, true);
    }
}