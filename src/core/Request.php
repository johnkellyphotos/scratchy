<?php

namespace core;

use controller\ErrorController;
use controller\PageNotFoundController;
use Throwable;

require_once __DIR__ . '/../defaults.php';

class Request
{
    public string $url;          // full path without query string
    public array $params;        // GET parameters
    public string $path;         // normalized path
    public array $segments;      // path segments

    public Controller $controller;

    public function __construct(?array $server = null, ?array $get = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['flash_post_data'] = $_POST;
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }

        $server = $server ?? $_SERVER;
        $get = $get ?? $_GET;

        $requestUri = $server['REQUEST_URI'] ?? '/';

        $parts = parse_url($requestUri);

        $this->url = $parts['path'] ?? '/';

        $this->params = $get;
        if (empty($this->params) && isset($parts['query'])) {
            parse_str($parts['query'], $this->params);
        }

        $this->path = '/' . trim($this->url, '/');
        if ($this->path === '//') {
            $this->path = '/';
        }

        $this->segments = array_values(array_filter(explode('/', trim($this->path, '/')), 'strlen'));

        $this->setController();
        $this->setControllerAction();
    }

    public function pageNotFound(): void
    {
        $this->controller = new PageNotFoundController();
        $this->controller->setAction($this->formatActionName(APP_DEFAULT_METHOD));
    }

    public static function error(?Throwable $exception = null): void
    {
        $ErrorController = new ErrorController();
        $ErrorController->setAction(APP_DEFAULT_METHOD);
        $ErrorController->output($exception->getMessage(), $exception);
        exit;
    }

    private function setControllerAction(): void
    {
        $actionName = $this->formatActionName($this->segments[1] ?? APP_DEFAULT_METHOD);

        if (method_exists($this->controller, $actionName)) {
            $this->controller->setAction($actionName);
        } else {
            $this->pageNotFound();
        }
    }

    private function setController(): void
    {
        $controllerName = $this->formatControllerName($this->segments[0] ?? APP_DEFAULT_CONTROLLER);

        $nameSpacedController = '\controller\\' . $controllerName;

        if (class_exists($nameSpacedController)) {
            try {
                $this->controller = new $nameSpacedController();
            } catch (Throwable $exception) {
                self::error($exception);
            }

        } else {
            $this->pageNotFound();
        }
    }

    private function formatActionName(string $actionName): string
    {
        $actionName = preg_replace('/[^a-z0-9]+/i', ' ', $actionName);
        $actionName = ucwords(strtolower(trim($actionName)));
        $actionName = str_replace(' ', '', $actionName);
        return lcfirst($actionName);
    }

    private function formatControllerName(string $controllerName): string
    {
        $controllerName = preg_replace('/[^a-z0-9]+/i', ' ', $controllerName);
        $controllerName = ucwords(strtolower(trim($controllerName)));
        return str_replace(' ', '', $controllerName) . 'Controller';
    }
}
