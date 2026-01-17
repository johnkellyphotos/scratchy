<?php

namespace core;

use Scratchy\component\WebPage;

class Controller
{
    private string $action;
    protected ?string $title = null;
    public readonly Data $data;
    protected int $responseCode = OKAY_STATUS_CODE;

    public function __construct()
    {
        $this->data = new Data();
    }

    public function output(...$arguments): void
    {
        $actionResult = $this->{$this->action}(...$arguments);
        $webPage = new WebPage($actionResult ?? []);

        $webPage->title->innerHtml($this->title ?? $this->createDefaultTitle());
        http_response_code($this->responseCode);
        $webPage->output();
    }

    private function createDefaultTitle(): string
    {
        return c(APP_NAME);
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }
}