<?php

namespace Scratchy\component;

use JsonException;
use Scratchy\elements\Element;

class Json extends Element
{
    public function __construct(protected array $data = [])
    {
    }

    /**
     * @throws JsonException
     */
    public function output(): void
    {
        http_response_code(200);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        echo json_encode(
            $this->data,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
