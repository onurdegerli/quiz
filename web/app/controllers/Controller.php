<?php
namespace App\Controllers;

use Core\View;

class Controller 
{
    /**
     * Config data.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Constructor.
     *
     * @param \DI\Container $container
     */
    public function __construct(\DI\Container $container)
    {
        $this->config = $container->get('config');
    }

    /**
     * Renders response data as a HTML content.
     *
     * @param string $file
     * @param array $params
     * @return void
     */
    protected function view(string $file, array $params = [])
    {
        $params['config'] = $this->config;

        View::render($file, $params);
    }

    /**
     * Renders response data as JSON content.
     *
     * @param array $params
     * @return void
     */
    protected function viewJson(array $params = [])
    {
        View::renderJson($params);
    }
}