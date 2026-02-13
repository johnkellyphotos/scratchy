<?php

namespace controller;

use core\Controller;
use Scratchy\elements\Element;
use Throwable;
use view\DatabaseController\IndexView;
use view\DatabaseController\TablesView;

/** @noinspection PhpUnused */
class DatabaseController extends Controller
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
        return new IndexView($this->data->post('execute_build'));
    }

    /** @noinspection PhpUnused */
    public function tables(): ?Element
    {
        return new TablesView();
    }
}
