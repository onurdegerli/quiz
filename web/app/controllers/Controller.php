<?php
namespace App\Controllers;

use Core\View;
use DI\Container;

class Controller 
{
    protected $config = [];

    public function __construct(Container $container)
    {
        $this->config = $container->get('config');
    }

    protected function view(string $file, array $params = []): void
    {
        $params['config'] = $this->config;

        View::render($file, $params);
    }

    protected function viewJson(array $params = [])
    {
        View::renderJson($params);
    }
}