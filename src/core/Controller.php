<?php

namespace core;

use Scratchy\component\WebPage;

class Controller
{
    private string $action;
    protected ?string $title = null;
    protected int $responseCode = OKAY_STATUS_CODE;

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
        return htmlspecialchars(APP_NAME);
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }
}