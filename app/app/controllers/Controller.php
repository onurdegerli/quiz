<?php declare(strict_types=1);

namespace App\Controllers;

use Core\View;

class Controller 
{
    protected function view(string $file, array $params = []): void
    {
        View::render($file, $params);
    }

    protected function viewJson(array $params = [])
    {
        View::renderJson($params);
    }
}