<?php

namespace controller;

use core\Controller;
use Scratchy\elements\Element;
use Scratchy\elements\h1;

class PageNotFoundController extends Controller
{
    public function __construct()
    {
    }

    public function index(): ?Element
    {
        $this->responseCode = PAGE_NOT_FOUND_STATUS_CODE;
        $this->title = 'Page not found!';
        return new h1('Page not found!');
    }
}