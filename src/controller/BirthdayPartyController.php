<?php

namespace controller;

use core\Controller;
use Scratchy\component\PageContent;

use Scratchy\elements\Element;
use Scratchy\elements\h1;

/** @noinspection PhpUnused */
class BirthdayPartyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function index(): ?Element
    {
        return new PageContent(
            new h1(content: 'John\'s birthday party sufferfests', classes: ['primary-color']),
        );
    }

    public function year(): Element
    {
        return new PageContent();
    }
}