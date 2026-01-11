<?php

namespace controller;

use core\Controller;
use Scratchy\component\PageContent;
use Scratchy\component\TextInput;
use Scratchy\elements\button;
use Scratchy\elements\Element;
use Scratchy\elements\form;
use Scratchy\elements\h1;
use Scratchy\elements\h2;
use Scratchy\elements\p;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): ?Element
    {
        return new PageContent(
            new h1(content: 'Welcome!', classes: ['primary-color']),
            new h2('Build websites quickly and cleanly.'),
        );
    }
}