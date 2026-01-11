<?php

namespace core;

use Throwable;

readonly class App
{
    private Request $Request;

    public function __construct(
        private bool $displayErrors = false,
    )
    {
        try {
            if ($this->displayErrors) {
                ini_set('display_errors', '1');
                ini_set('display_startup_errors', '1');
                error_reporting(E_ALL);
            }
            $this->Request = new Request();
        } catch (Throwable $error) {
            Request::error($error);
        }
    }

    public function serve(): void
    {
        try {
            $this->Request->controller->output();
        } catch (Throwable $error) {
            Request::error($error);
        }
    }
}