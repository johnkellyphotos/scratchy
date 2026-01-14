<?php

namespace controller;

use core\Controller;
use Scratchy\component\PageContent;
use Scratchy\elements\Element;
use Scratchy\elements\h1;
use Scratchy\elements\h2;

class DatabaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): ?Element
    {
        return new PageContent(
            new h1(content: 'Configure a database', classes: ['primary-color']),
            new h2('Build websites quickly and cleanly.'),
        );
    }
}