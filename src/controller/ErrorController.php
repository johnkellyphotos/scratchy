<?php

namespace controller;

use core\Controller;
use Exception;
use Scratchy\component\PageContent;
use Scratchy\elements\Element;
use Scratchy\elements\h1;
use Scratchy\elements\li;
use Scratchy\elements\ol;
use Throwable;

class ErrorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->responseCode = INTERNAL_SERVER_ERROR_CODE;
    }

    public function index(?string $message = null, ?Throwable $exception = null): ?Element
    {
        $message ??= "We experienced an error loading this page.";
        $PageContents = new PageContent(new h1(content: $message));
        if ($exception) {
            $ol = new ol();
            foreach ($exception->getTrace() as $error) {
                $file = $error['file'] ?? '[internal]';
                $line = $error['line'] ?? '';
                $class = $error['class'] ?? '';
                $type = $error['type'] ?? '';
                $function = $error['function'] ?? '';
                $ol->append(new li(
                    content: "{$class}{$type}{$function} @ {$file}:{$line}"
                ));
            }
            $PageContents->append($ol);
        }
        return $PageContents;
    }

    /**
     * @throws Exception
     */
    public function throwAnError(): ?Element
    {
        rand(1, 1) && throw new Exception("Here is an error!");
        return new PageContent(
            new h1(content: 'Oh no!'),
        );
    }
}