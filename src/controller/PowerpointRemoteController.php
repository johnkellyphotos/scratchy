<?php

namespace controller;

use core\Controller;
use Scratchy\elements\Element;
use Throwable;
use view\PowerpointRemoteController\IndexView;

/** @noinspection PhpUnused */
class PowerpointRemoteController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Throwable
     */
    /** @noinspection PhpUnused */
    public function index(): ?Element
    {
        return new IndexView();
    }
}