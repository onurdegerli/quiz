<?php

namespace Core\Http;

use Core\Exceptions\ViewException;
use Core\View;

class Response
{
    /**
     * @param string $viewFile
     * @param array $data
     * @return $this
     * @throws ViewException
     */
    public function responseHtml(string $viewFile, array $data = []): self
    {
        View::render($viewFile, $data);

        return $this;
    }

    public function responseJson(array $data = []): self
    {
        View::renderJson($data);

        return $this;
    }
}