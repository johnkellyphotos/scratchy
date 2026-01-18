<?php

namespace controller;

use core\Controller;
use Scratchy\elements\Element;
use Throwable;
use view\DatabaseController\IndexView;
use view\DatabaseController\ViewUserActivityView;
use view\DatabaseController\ViewUsersView;

/** @noinspection PhpUnused */
class DatabaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function viewUsers(): ViewUsersView
    {
        return new ViewUsersView($this->data->post('has_been_submitted') ?? false);
    }

    /** @noinspection PhpUnused */
    public function viewUserActivity(): ViewUserActivityView
    {
        return new ViewUserActivityView($this->data->post('has_been_submitted') ?? false);
    }

    /**
     * @throws Throwable
     */
    /** @noinspection PhpUnused */
    public function index(): ?Element
    {
        return new IndexView($this->data->post('execute_build'));
    }
}