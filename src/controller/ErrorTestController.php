<?php

namespace controller;

use core\Controller;
use Exception;
use Scratchy\elements\Element;

class ErrorTestController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function index(): ?Element
    {
        throw new Exception('Test exception: this route is meant to trigger the error page.');
    }
}
