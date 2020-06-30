<?php

namespace Core\Http;

use Core\Exceptions\ViewException;
use Core\View\View;

class Response
{
    /**
     * @param string $viewFile
     * @param array $data
     * @param int $responseCode
     * @return $this
     * @throws ViewException
     */
    public function responseHtml(string $viewFile, array $data = [], int $responseCode = 200): self
    {
        http_response_code($responseCode);
        View::render($viewFile, $data);

        return $this;
    }

    public function responseJson(array $data = [], int $responseCode = 200): self
    {
        http_response_code($responseCode);
        header('Content-Type: application/json');
        echo json_encode($data);

        return $this;
    }
}