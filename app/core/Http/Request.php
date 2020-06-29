<?php

namespace Core\Http;

class Request
{
    public array $payload;
    public array $get;
    public array $post;

    public function create(): self
    {
        $this->payload = filter_var_array($this->getRequestPayload(), FILTER_SANITIZE_STRING);
        $this->get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
        $this->post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        unset($_GET);
        unset($_POST);
        unset($_REQUEST);

        return $this;
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